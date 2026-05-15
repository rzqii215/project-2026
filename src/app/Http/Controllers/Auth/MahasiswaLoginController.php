<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProfilMahasiswa;
use App\Models\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MahasiswaLoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login-mahasiswa');
    }

    public function showRegister(): View
    {
        return view('auth.register-mahasiswa');
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
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        if ($user->role !== User::ROLE_MAHASISWA) {
            return back()
                ->withErrors([
                    'email' => 'Akun ini bukan akun mahasiswa.',
                ])
                ->onlyInput('email');
        }

        $this->setLoginSession($request, $user);

        return redirect('/mahasiswa');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],

            'nomor_hp' => ['nullable', 'string', 'max:30'],

            'nim' => [
                'required',
                'string',
                'max:50',
                Rule::unique('profil_mahasiswas', 'nim'),
            ],

            'program_studi' => ['required', 'string', 'max:255'],
            'fakultas' => ['required', 'string', 'max:255'],

            'angkatan' => [
                'required',
                'integer',
                'min:2000',
                'max:' . now()->year,
            ],

            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_hp' => $data['nomor_hp'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_MAHASISWA,
        ]);

        ProfilMahasiswa::create([
            'user_id' => $user->id,
            'nim' => $data['nim'],
            'program_studi' => $data['program_studi'],
            'fakultas' => $data['fakultas'],
            'angkatan' => $data['angkatan'],
        ]);

        return redirect('/login-mahasiswa')
            ->with('success', 'Akun mahasiswa berhasil dibuat. Silakan login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $sessionKey = $this->getLoginSessionKey();

        $request->session()->forget($sessionKey);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-mahasiswa');
    }

    private function setLoginSession(Request $request, User $user): void
    {
        $request->session()->put(
            $this->getLoginSessionKey(),
            $user->getAuthIdentifier()
        );

        $request->session()->regenerate();
    }

    private function getLoginSessionKey(): string
    {
        return 'login_web_' . sha1(SessionGuard::class);
    }
}