<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;


class CartManagement
{
    // ADD ITEM TO CART
    
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

    // REMOVE ITEM FROM CART
    
    public static function removeCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }

        // reindex array
        $cart_items = array_values($cart_items);

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // COOKIE HANDLER
    
    public static function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
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

    // INCREMENT QUANTITY
   
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

    // DECREMENT QUANTITY
    
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

    // GRAND TOTAL
    
    public static function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}
