<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerRequestController extends Controller
{
    /**
     * Tampilkan form pengajuan seller
     */
    public function create()
    {
        $user = Auth::user();

        // Check jika user sudah menjadi seller
        if ($user->role === 'seller') {
            return redirect()->route('user.profile.index')
                ->with('info', 'Anda sudah menjadi seller. Silakan akses dashboard seller Anda.');
        }

        // Check jika ada pengajuan yang sedang pending
        $existingRequest = SellerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('user.seller.status')
                ->with('info', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        return view('user.seller.create');
    }

    /**
     * Simpan pengajuan seller
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Double check
        if ($user->role === 'seller') {
            return redirect()->route('user.profile.index')
                ->with('info', 'Anda sudah menjadi seller.');
        }

        $existingRequest = SellerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('user.seller.status')
                ->with('info', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        // Validasi input
        $validated = $request->validate([
            'store_name' => 'required|string|max:255|unique:seller_requests,store_name',
            'store_description' => 'required|string|min:50|max:1000',
            'agree_terms' => 'required|accepted',
        ], [
            'store_name.required' => 'Nama toko wajib diisi.',
            'store_name.unique' => 'Nama toko sudah digunakan. Silakan pilih nama lain.',
            'store_description.required' => 'Deskripsi toko wajib diisi.',
            'store_description.min' => 'Deskripsi toko minimal 50 karakter.',
            'store_description.max' => 'Deskripsi toko maksimal 1000 karakter.',
            'agree_terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        // Buat pengajuan seller baru
        $sellerRequest = SellerRequest::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            'store_description' => $validated['store_description'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.seller.status')
            ->with('success', 'Pengajuan menjadi seller berhasil dikirim! Kami akan meninjau pengajuan Anda dalam 1-3 hari kerja.');
    }

    /**
     * Tampilkan status pengajuan seller
     */
    public function status()
    {
        $user = Auth::user();

        // Cek apakah user sudah menjadi seller (bisa jadi baru saja diapprove)
        if ($user->role === 'seller') {
            return redirect()->route('user.profile.index')
                ->with('success', 'Selamat! Anda sekarang adalah seller. Silakan akses dashboard seller Anda.');
        }

        // Ambil pengajuan terbaru user
        $sellerRequest = SellerRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$sellerRequest) {
            return redirect()->route('user.seller.create')
                ->with('info', 'Anda belum memiliki pengajuan seller.');
        }

        return view('user.seller.status', compact('sellerRequest'));
    }

    /**
     * Batalkan pengajuan pending
     */
    public function cancel()
    {
        $user = Auth::user();

        $sellerRequest = SellerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$sellerRequest) {
            return redirect()->route('user.profile.index')
                ->with('error', 'Tidak ada pengajuan yang dapat dibatalkan.');
        }

        // Hapus pengajuan
        $sellerRequest->delete();

        return redirect()->route('user.profile.index')
            ->with('success', 'Pengajuan seller berhasil dibatalkan.');
    }
}
