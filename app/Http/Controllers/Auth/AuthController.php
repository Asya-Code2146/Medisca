<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $loginInput = $request->validated('login');
        $password = $request->validated('password');

        $user = User::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        if (! $user && ! str_contains($loginInput, '@')) {
            $user = User::where('email', $loginInput.'@medisca.sch.id')->first();
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama pengguna atau kata sandi salah. Silakan periksa kembali.',
                ], 422);
            }

            return back()->withErrors(['login' => 'Nama pengguna atau kata sandi salah.']);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'redirect' => $user->role === 'admin' ? '/admin' : '/dashboard',
            ]);
        }

        return redirect()->intended($user->role === 'admin' ? '/admin' : '/dashboard');
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil.',
            ]);
        }

        return redirect('/');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'authenticated' => false,
                'user' => null,
            ]);
        }

        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }
}
