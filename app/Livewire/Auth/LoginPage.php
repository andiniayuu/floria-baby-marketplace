<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login')]
class LoginPage extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = auth()->user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect('/admin');
            } elseif ($user->role === 'seller') {
                return redirect('/seller');
            } else {
                // User biasa redirect ke homepage
                return redirect('/');
            }
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
