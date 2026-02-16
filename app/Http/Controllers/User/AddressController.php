<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display listing of user addresses
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();

        return view('user.addresses.index', compact('addresses'));
    }

    /**
     * Show form for creating new address
     */
    public function create()
    {
        return view('user.addresses.create');
    }

    /**
     * Store new address
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        UserAddress::create($validated);

        if ($request->from === 'checkout') {
            session(['checkout_address_id' => $address->id]);

            return redirect()->route('checkout.index')
                ->with('success', 'Alamat berhasil ditambahkan');
        }
        return redirect()
            ->route('user.addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan');
    }

    /**
     * Show form for editing address
     */
    public function edit(UserAddress $address)
    {
        // Pastikan address milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.addresses.edit', compact('address'));
    }

    /**
     * Update address
     */
    public function update(Request $request, UserAddress $address)
    {
        // Pastikan address milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'subdistrict' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'notes' => 'nullable|string',
            'is_primary' => 'boolean',
        ]);

        $address->update($validated);

        return redirect()
            ->route('user.addresses.index')
            ->with('success', 'Alamat berhasil diperbarui');
    }

    /**
     * Delete address
     */
    public function destroy(UserAddress $address)
    {
        // Pastikan address milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Cegah hapus jika alamat primary dan masih ada alamat lain
        if ($address->is_primary && Auth::user()->addresses()->count() > 1) {
            return back()->with('error', 'Tidak dapat menghapus alamat utama. Silakan set alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        return redirect()
            ->route('user.addresses.index')
            ->with('success', 'Alamat berhasil dihapus');
    }

    /**
     * Set address as primary
     */
    public function setPrimary(UserAddress $address)
    {
        // Pastikan address milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah');
    }
}
