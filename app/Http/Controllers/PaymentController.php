<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans config
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Show payment page
     */
    public function show($orderId)
    {
        $order = Order::with(['items.product', 'user', 'address'])->findOrFail($orderId);

        // Authorization check
        if (auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized access');
        }

        // Generate snap token if not exists
        if (!$order->snap_token) {
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'customer_details' => [
                    'first_name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->address->phone ?? '',
                ],
                'item_details' => $this->getItemDetails($order),
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return back()->with('error', 'Payment creation failed: ' . $e->getMessage());
            }
        }

        return view('user.payment.show', [
            'order' => $order,
            'snapToken' => $order->snap_token,
            'clientKey' => config('services.midtrans.client_key'),
        ]);
    }

    /**
     * Prepare item details
     */
    private function getItemDetails($order)
    {
        $items = [];
        
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->unit_amount,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }

        // Add shipping
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping - ' . ucfirst($order->shipping_method),
            ];
        }

        return $items;
    }

    /**
     * Handle notification webhook
     */
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $order = Order::where('order_number', $notification->order_id)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $transaction = $notification->transaction_status;
            $fraud = $notification->fraud_status;

            switch ($transaction) {
                case 'capture':
                    if ($fraud == 'accept') {
                        $order->payment_status = 'paid';
                        $order->status = 'processing';
                    }
                    break;

                case 'settlement':
                    $order->payment_status = 'paid';
                    $order->status = 'processing';
                    break;

                case 'pending':
                    $order->payment_status = 'pending';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $order->payment_status = 'failed';
                    $order->status = 'cancelled';
                    break;
            }

            $order->save();

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Payment success
     */
    public function success(Request $request)
    {
        $orderNumber = $request->query('order_id');
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return redirect()->route('user.order.success', ['order_id' => $order->id])
            ->with('success', 'Payment completed successfully!');
    }
}