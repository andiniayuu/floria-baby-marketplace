<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's addresses
        $addresses = $user->addresses()->get();

        // Get primary address
        $primaryAddress = $user->addresses()->where('is_primary', true)->first();

        // Ambil cart dari session (sesuaikan key session-mu)
        $cartItems = session('cart', []);

        return view('user.checkout.index', compact('addresses', 'primaryAddress', 'cartItems'));
    }

    /**
     * Process checkout
     * VERSI MULTI-SELLER: Membuat order terpisah per seller
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Pastikan alamat milik user yang login
        $address = UserAddress::where('id', $validated['address_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Ambil cart dari session
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Cart kosong.');
        }

        // Group cart items by seller_id
        $itemsBySeller = $this->groupCartBySeller($cartItems);

        // Tentukan shipping cost
        $shippingCostPerSeller = $validated['shipping_method'] === 'express' ? 25000 : 15000;

        DB::beginTransaction();
        try {
            $createdOrders = [];

            // Buat order terpisah untuk setiap seller
            foreach ($itemsBySeller as $sellerId => $items) {
                // Hitung subtotal untuk seller ini
                $subtotal = collect($items)->sum(fn($item) => $item['quantity'] * $item['unit_amount']);

                // Grand total untuk seller ini
                $grandTotal = $subtotal + $shippingCostPerSeller;

                // Buat order
                $order = Order::create([
                    'user_id'        => Auth::id(),
                    'seller_id'      => $sellerId,
                    'address_id'     => $address->id,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'pending',
                    'shipping_method' => $validated['shipping_method'],
                    'shipping_cost'  => $shippingCostPerSeller,
                    'grand_total'    => $grandTotal,
                    'notes'          => $validated['notes'] ?? null,
                    'status'         => 'pending',
                ]);

                // Simpan order items untuk seller ini
                foreach ($items as $item) {
                    $product = \App\Models\Product::find($item['product_id']);

                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_amount' => $item['unit_amount'],
                        'total_amount' => $item['quantity'] * $item['unit_amount'],
                        'price' => $product->price ?? 0,
                    ]);
                }


                $createdOrders[] = $order;
            }

            DB::commit();

            // Kosongkan cart
            session()->forget('cart');

            // Jika hanya 1 order, redirect ke thank you page
            if (count($createdOrders) === 1) {
                return redirect()->route('user.checkout.thankyou', $createdOrders[0]->id);
            }

            // Jika multiple orders, redirect ke halaman ringkasan semua order
            return redirect()->route('user.orders.index')->with('success', 'Berhasil membuat ' . count($createdOrders) . ' order!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Group cart items by seller_id
     */
    private function groupCartBySeller(array $cartItems)
    {
        $grouped = [];

        foreach ($cartItems as $item) {

            $product = \App\Models\Product::find($item['product_id']);

            if (!$product || !$product->seller_id) {
                continue;
            }

            $sellerId = $product->seller_id;

            $grouped[$sellerId][] = [
                ...$item,
                'seller_id' => $sellerId,
            ];
        }

        return $grouped;
    }


    /**
     * Get address details via AJAX
     */
    public function getAddress($addressId)
    {
        $address = UserAddress::where('id', $addressId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }
}
