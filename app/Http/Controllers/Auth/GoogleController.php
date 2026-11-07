<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $email = $googleUser->getEmail();
            $googleId = $googleUser->getId();
            $name = $googleUser->getName() ?? 'Pengguna Google';

            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                if (empty($user->google_id)) {
                    $user->google_id = $googleId;
                    $user->save();
                }
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => null,
                    'role' => 'pihak_sekolah',
                ]);
            }

            Auth::login($user, true);

            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/dashboard');
        } catch (Exception $e) {
            return redirect('/?login_error=google_failed');
        }
    }
}
