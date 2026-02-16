<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show user profile dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Total orders & addresses bisa ambil dari relasi user
        $totalOrders = $user->orders()->count();
        $totalAddresses = $user->addresses()->count();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        // Ambil request terakhir jika ada
        $sellerRequest = \App\Models\SellerRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('user.profile.index', compact(
            'user',
            'sellerRequest',
            'totalOrders',
            'totalAddresses',
            'recentOrders'
        ));
    }



    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()
            ->route('user.profile.edit')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Show change password form
     */
    public function editPassword()
    {
        return view('user.profile.password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()
            ->route('user.profile.password')
            ->with('success', 'Password berhasil diubah');
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus');
    }
}
