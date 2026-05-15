<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProfilMahasiswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MahasiswaLoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login-mahasiswa');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return redirect('/login-mahasiswa')
                ->withErrors([
                    'email' => 'Session login tidak valid.',
                ]);
        }

        if ($user->role !== User::ROLE_MAHASISWA) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login-mahasiswa')
                ->withErrors([
                    'email' => 'Akun ini bukan akun mahasiswa.',
                ])
                ->onlyInput('email');
        }

        ProfilMahasiswa::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'nim' => '000000',
                'program_studi' => 'Belum Diisi',
                'fakultas' => 'Belum Diisi',
                'angkatan' => now()->year,
            ]
        );

        return redirect('/mahasiswa');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-mahasiswa');
    }
}