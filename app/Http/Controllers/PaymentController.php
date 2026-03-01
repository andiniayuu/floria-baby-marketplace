<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    /**
     * Fallback: generate snap token jika belum ada.
     */
    public function show($orderId)
    {
        $order = Order::with(['items.product', 'user', 'address'])->findOrFail($orderId);

        if (auth()->id() !== $order->user_id) abort(403);

        if ($order->isPaid()) {
            return redirect()->route('user.order.success', $order->id);
        }

        if ($order->payment_method !== 'midtrans') {
            return redirect()->route('user.my-orders.show', $order->id);
        }

        if (!$order->snap_token) {
            try {
                $params    = $this->buildMidtransParams($order);
                $snapToken = Snap::getSnapToken($params);
                $order->update([
                    'snap_token'        => $snapToken,
                    'midtrans_order_id' => $params['transaction_details']['order_id'],
                ]);
            } catch (\Exception $e) {
                return redirect()
                    ->route('user.my-orders.show', $order->id)
                    ->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
            }
        }

        return redirect()->route('user.my-orders.show', $order->id);
    }

    /**
     * ✅ SUCCESS — dipanggil oleh onSuccess JS Snap.
     * (GoPay, QRIS, CC, ShopeePay yang langsung settled)
     *
     * Route: GET /user/payment/success/{id}
     * Name: user.payment.success
     *
     * Langsung markAsPaid() di sini agar status DB langsung update
     * tanpa perlu menunggu webhook (yang tidak bisa jalan di localhost).
     */
    public function success($orderId)
    {
        $order = Order::with(['items.product', 'address'])->findOrFail($orderId);

        if (auth()->id() !== $order->user_id) abort(403);

        // ✅ Guard: hanya proses Midtrans di sini
        if ($order->payment_method !== 'midtrans') {
            return redirect()->route('user.order.success', $order->id);
        }

        // Update DB langsung — tidak bergantung pada webhook
        if (!$order->isPaid()) {
            $order->markAsPaid();
            Log::info('Order paid via onSuccess JS', ['order_id' => $order->id]);
        }

        $order->refresh();

        return redirect()->route('user.order.success', $order->id)
            ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
    }

    /**
     * ✅ ORDER SUCCESS PAGE — halaman konfirmasi setelah pembayaran.
     *
     * Route: GET /user/order/success/{id}
     * Name: user.order.success
     */
    public function orderSuccess($orderId)
    {
         $order = Order::with(['items.product', 'address'])->findOrFail($orderId);

        if (auth()->id() !== $order->user_id) abort(403);

        return view('user.order-success', compact('order'));
    }

    /**
     * ✅ FINISH — dipanggil saat user klik "Return to merchant's page" di popup Midtrans.
     * Bisa membawa query string: transaction_status, fraud_status, dll.
     *
     * Route: GET /user/payment/finish/{id}
     * Name: user.payment.finish
     */
    public function finish($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (auth()->id() !== $order->user_id) abort(403);

        // Refresh untuk cek apakah webhook sudah update DB duluan
        $order->refresh();

        $transactionStatus = request()->query('transaction_status');
        $fraudStatus       = request()->query('fraud_status');

        $isSuccess = in_array($transactionStatus, ['settlement', 'capture'])
            && ($fraudStatus === 'accept' || $fraudStatus === null);

        if ($isSuccess || $order->isPaid()) {
            if (!$order->isPaid()) {
                $order->markAsPaid();
                Log::info('Order paid via finish callback', ['order_id' => $order->id]);
            }

            return redirect()
                ->route('user.order.success', $order->id)
                ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
        }

        // VA Bank: masih pending, user belum transfer
        return redirect()
            ->route('user.my-orders.show', $order->id)
            ->with('info', 'Pesanan dibuat! Selesaikan transfer ke Virtual Account Anda. Status akan diperbarui otomatis.');
    }

    /**
     * ✅ WEBHOOK dari server Midtrans.
     *
     * PENTING — exclude dari CSRF di VerifyCsrfToken.php:
     *   protected $except = ['payment/notification'];
     *
     * Di production, daftarkan di dashboard Midtrans:
     * Settings -> Configuration -> Payment Notification URL
     * -> https://yourdomain.com/payment/notification
     *
     * Route: POST /payment/notification
     * Name: payment.notification
     */
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            Log::info('Midtrans Webhook Masuk', [
                'order_id' => $notification->order_id,
                'status'   => $notification->transaction_status,
                'type'     => $notification->payment_type,
                'fraud'    => $notification->fraud_status,
            ]);

            // Format order_id Midtrans: ORDER-{id}-{timestamp}
            preg_match('/ORDER-(\d+)-/', $notification->order_id, $matches);
            $originalOrderId = $matches[1] ?? null;

            if (!$originalOrderId) {
                Log::warning('Format order_id tidak valid', ['order_id' => $notification->order_id]);
                return response()->json(['message' => 'Invalid order ID format'], 400);
            }

            $order = Order::find($originalOrderId);

            if (!$order) {
                Log::warning('Order tidak ditemukan', ['id' => $originalOrderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update info transaksi Midtrans
            $order->update([
                'midtrans_transaction_id' => $notification->transaction_id,
                'midtrans_payment_type'   => $notification->payment_type,
            ]);

            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;

            switch ($transactionStatus) {

                case 'capture':
                    if ($fraudStatus === 'accept') {
                        $order->markAsPaid();
                        Log::info('Paid via capture+accept', ['order_id' => $order->id]);
                    } elseif ($fraudStatus === 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                        Log::warning('Payment challenge (fraud review)', ['order_id' => $order->id]);
                    }
                    break;

                case 'settlement':
                    $order->markAsPaid();
                    Log::info('Paid via settlement', ['order_id' => $order->id]);
                    break;

                case 'pending':
                    if (!$order->isPaid()) {
                        $order->update(['payment_status' => 'pending', 'status' => 'pending']);
                        Log::info('Pending - VA belum ditransfer', ['order_id' => $order->id]);
                    }
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                    Log::warning('Pembayaran gagal', ['order_id' => $order->id, 'status' => $transactionStatus]);
                    break;
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Webhook Error', ['error' => $e->getMessage(), 'line' => $e->getLine()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check status via AJAX untuk polling.
     */
    public function checkStatus($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (auth()->id() !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'paid'           => $order->isPaid(),
            'status'         => $order->status,
            'payment_status' => $order->payment_status,
            'paid_at'        => $order->paid_at?->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
        ]);
    }

    private function buildMidtransParams(Order $order): array
    {
        $midtransOrderId = 'ORDER-' . $order->id . '-' . time();

        return [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email'      => $order->user->email,
                'phone'      => $order->address->phone ?? '',
            ],
            'item_details'     => $this->getItemDetails($order),
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'mandiri_va',
                'permata_va',
                'other_va',
                'gopay',
                'shopeepay',
                'qris',
            ],
            'callbacks' => [
                'finish' => route('user.payment.finish', $order->id),
            ],
        ];
    }

    private function getItemDetails(Order $order): array
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id'       => $item->product_id,
                'price'    => (int) $item->unit_amount,
                'quantity' => $item->quantity,
                'name'     => substr($item->product->name, 0, 50),
            ];
        }

        if ($order->shipping_cost > 0) {
            $items[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim - ' . ucfirst($order->shipping_method),
            ];
        }

        return $items;
    }
}
