<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private function getRedirectUri(): string
    {
        $uri = env('GOOGLE_REDIRECT_URI');
        if (! empty($uri)) {
            return $uri;
        }

        $host = request()->getHttpHost();
        $scheme = request()->secure() || str_contains($host, 'vercel.app') ? 'https' : 'http';

        return $scheme.'://'.$host.'/auth/google/callback';
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl($this->getRedirectUri())
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl($this->getRedirectUri())
                ->stateless()
                ->user();

            $email = strtolower(trim($googleUser->getEmail()));
            $googleId = $googleUser->getId();
            $name = $googleUser->getName() ?? 'Pengguna Google';
            $avatar = $googleUser->getAvatar();

            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            $adminEmails = [
                'mieayama095@gmail.com',
                'assyifaulmuthmainnah12@gmail.com',
                'hizriuswa15@gmail.com',
                'mafazhasna5@gmail.com',
                'nadiraazzahra1329@gmail.com',
            ];

            if (in_array($email, $adminEmails, true) || str_starts_with($email, 'admin@')) {
                $role = 'admin';
            } elseif (str_starts_with($email, 'sekolah@')) {
                $role = 'pihak_sekolah';
            } else {
                $role = 'guru_uks';
            }

            if ($user) {
                if (empty($user->google_id)) {
                    $user->google_id = $googleId;
                }
                if (! empty($avatar)) {
                    $user->avatar = $avatar;
                }
                $user->role = $role;
                $user->save();
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'password' => null,
                    'role' => $role,
                ]);
            }

            Auth::login($user, true);

            if ($user->role === 'admin') {
                return redirect('/admin');
            }

            return redirect('/dashboard');
        } catch (Exception $e) {
            Log::error('Google OAuth error: '.$e->getMessage());
            return redirect('/?login_error='.urlencode($e->getMessage()));
        }
    }
}
