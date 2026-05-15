<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login-admin');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password admin salah.',
                ])
                ->onlyInput('email');
        }

        if (! in_array($user->role, ['admin', 'owner'], true)) {
            return back()
                ->withErrors([
                    'email' => 'Akun ini bukan akun admin.',
                ])
                ->onlyInput('email');
        }

        $request->session()->put(
            $this->getLoginSessionKey(),
            $user->getAuthIdentifier()
        );

        $request->session()->regenerate();

        return redirect('/admin');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget($this->getLoginSessionKey());
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-admin');
    }

    public function resetAdmin(): \Illuminate\Http\JsonResponse
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'nomor_hp' => null,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $admin = DB::table('users')
            ->where('email', 'admin@admin.com')
            ->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin berhasil direset.',
            'email' => $admin->email,
            'role' => $admin->role,
            'login_url' => url('/login-admin'),
            'login_email' => 'admin@admin.com',
            'login_password' => 'password',
            'password_check' => Hash::check('password', $admin->password),
        ]);
    }

    private function getLoginSessionKey(): string
    {
        return 'login_web_' . sha1(SessionGuard::class);
    }
}