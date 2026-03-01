<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class FixSellerOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::with('items.product')->get();
        $updated = 0;
        
        foreach ($orders as $order) {
            if ($order->seller_id) {
                continue; // Skip if already has seller_id
            }
            
            $firstItem = $order->items->first();
            
            if ($firstItem && $firstItem->product) {
                $order->update(['seller_id' => $firstItem->product->seller_id]);
                $updated++;
            }
        }
        
        $this->command->info("✓ Updated {$updated} orders with seller_id");
    }
}