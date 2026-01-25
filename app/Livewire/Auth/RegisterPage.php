<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Register')]

class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    // Register User
    public function save()
    {
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        // Save to DB
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'user', // Default role adalah user
        ]);

        // Login User
        Auth::login($user);

        // Redirect to Homepage
        return Redirect()->intended();
    }



    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
