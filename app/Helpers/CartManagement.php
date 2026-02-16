<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    // ============================================================
    // ADD ITEM TO CART
    // ============================================================

    public static function addItemToCart($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();
        $existing_index = null;

        foreach ($cart_items as $index => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_index = $index;
                break;
            }
        }

        if ($existing_index !== null) {
            $cart_items[$existing_index]['quantity']++;
            $cart_items[$existing_index]['total_amount'] =
                $cart_items[$existing_index]['quantity'] *
                $cart_items[$existing_index]['unit_amount'];
        } else {
            $product = Product::find($product_id, ['id', 'name', 'price', 'images']);

            if ($product) {
                $cart_items[] = [
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'image'        => $product->images[0] ?? null,
                    'quantity'     => 1,
                    'unit_amount'  => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);

        return count($cart_items);
    }

    // ============================================================
    // ADD ITEM TO CART WITH QUANTITY
    // ============================================================

    public static function addItemToCartWithQty($product_id, $qty)
    {
        $cart_items = self::getCartItemsFromCookie();
        $existing_index = null;

        foreach ($cart_items as $index => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_index = $index;
                break;
            }
        }

        if ($existing_index !== null) {
            // 🔧 FIX: Typo di kode lama ($qty = 1 seharusnya $qty)
            $cart_items[$existing_index]['quantity'] = $qty;
            $cart_items[$existing_index]['total_amount'] =
                $cart_items[$existing_index]['quantity'] *
                $cart_items[$existing_index]['unit_amount'];
        } else {
            $product = Product::find($product_id, ['id', 'name', 'price', 'images']);

            if ($product) {
                $cart_items[] = [
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'image'        => $product->images[0] ?? null,
                    'quantity'     => $qty,
                    'unit_amount'  => $product->price,
                    'total_amount' => $product->price * $qty, // 🔧 FIX: Kali qty
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);

        return count($cart_items);
    }

    // ============================================================
    // REMOVE ITEM FROM CART
    // ============================================================

    public static function removeCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }

        // Reindex array
        $cart_items = array_values($cart_items);

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // ============================================================
    // INCREMENT QUANTITY
    // ============================================================

    public static function incrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] =
                    $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // ============================================================
    // DECREMENT QUANTITY
    // ============================================================

    public static function decrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['quantity'] > 1) {
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] =
                    $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // ============================================================
    // COOKIE HANDLERS
    // ============================================================

    public static function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30); // 30 days
    }

    /**
     * 🔄 UPDATE: Update cart items in cookie
     * Alias untuk addCartItemsToCookie() - digunakan setelah sinkronisasi
     */
    public static function updateCartItemsInCookie($cart_items)
    {
        self::addCartItemsToCookie($cart_items);
    }

    public static function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    public static function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        return $cart_items ?: [];
    }

    // ============================================================
    // 🆕 NEW METHODS - VALIDATION & UTILITIES
    // ============================================================

    /**
     * 📊 GET: Total items count in cart
     */
    public static function getCartItemsCount()
    {
        $cart_items = self::getCartItemsFromCookie();
        return collect($cart_items)->sum('quantity');
    }

    /**
     * 🔒 VALIDATE: Validate cart items against database
     * Returns array with validation results
     */
    public static function validateCartItems()
    {
        $cart_items = self::getCartItemsFromCookie();

        if (empty($cart_items)) {
            return [
                'valid' => true,
                'items' => [],
                'errors' => [],
            ];
        }

        $productIds = collect($cart_items)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $validItems = [];
        $errors = [];

        foreach ($cart_items as $item) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                $errors[] = "Product {$item['name']} not found";
                continue;
            }

            if ($product->stock < $item['quantity']) {
                $errors[] = "Insufficient stock for {$item['name']}";

                if ($product->stock > 0) {
                    $item['quantity'] = $product->stock;
                    $item['total_amount'] = $product->stock * $item['unit_amount'];
                } else {
                    continue; // Skip out of stock items
                }
            }

            // Update price if changed
            if ($product->price != $item['unit_amount']) {
                $item['unit_amount'] = $product->price;
                $item['total_amount'] = $item['quantity'] * $product->price;
            }

            $validItems[] = $item;
        }

        // Update cookie with validated items
        self::updateCartItemsInCookie($validItems);

        return [
            'valid' => empty($errors),
            'items' => $validItems,
            'errors' => $errors,
        ];
    }

    /**
     * 🔍 FIND: Get single cart item by product_id
     */
    public static function getCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();
        return collect($cart_items)->firstWhere('product_id', $product_id);
    }

    /**
     * ✅ CHECK: Check if product exists in cart
     */
    public static function isInCart($product_id)
    {
        return self::getCartItem($product_id) !== null;
    }

    // ============================================================
    // GRAND TOTAL (EXISTING)
    // ============================================================

    public static function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}
