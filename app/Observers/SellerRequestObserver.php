<?php

namespace App\Observers;

use App\Models\SellerRequest;

class SellerRequestObserver
{
    public function updated(SellerRequest $sellerRequest): void
    {
        if (! $sellerRequest->isDirty('status')) {
            return;
        }

        $user = $sellerRequest->user;

        if (! $user) {
            return;
        }

        // approved
        if ($sellerRequest->status === 'approved') {
            $user->update([
                'role' => 'seller',
                'seller_status' => 'approved',
            ]);

            logger()->info('User promoted to seller', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        // rejected
        if ($sellerRequest->status === 'rejected') {
            $user->update([
                'role' => 'user',
                'seller_status' => null,
            ]);

            logger()->info('Seller request rejected', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }
    }

    public function deleted(SellerRequest $sellerRequest): void
    {
        $user = $sellerRequest->user;

        if (! $user) {
            return;
        }

        // request dihapus, kembalikan role
        $user->update([
            'role' => 'user',
            'seller_status' => null,
            'shop_name' => null,
            'shop_description' => null,
        ]);

        logger()->info('Seller request deleted, role reverted', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
