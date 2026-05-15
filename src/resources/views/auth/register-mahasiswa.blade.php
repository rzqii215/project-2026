<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - E-Portfolio</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #09090b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .title {
            text-align: center;
            margin-bottom: 28px;
        }

        .title h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .title p {
            margin: 0;
            color: #a1a1aa;
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        input {
            width: 100%;
            border: 1px solid #3f3f46;
            border-radius: 10px;
            background: #27272a;
            color: #ffffff;
            padding: 13px 14px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #f59e0b;
        }

        .error {
            background: #7f1d1d;
            color: #fecaca;
            border: 1px solid #991b1b;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 13px 16px;
            background: #f59e0b;
            color: #111827;
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
        }

        .links {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 14px;
        }

        .links a {
            color: #fbbf24;
            text-decoration: none;
        }

        @media (max-width: 700px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="title">
            <h1>Daftar Akun Mahasiswa</h1>
            <p>Buat akun untuk mengelola E-Portfolio prestasi mahasiswa</p>
        </div>

        @if ($errors->any())
            <div class="error">
                <strong>Data belum valid:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.mahasiswa.post') }}">
            @csrf

            <div class="grid">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="nomor_hp">Nomor HP</label>
                    <input
                        type="text"
                        id="nomor_hp"
                        name="nomor_hp"
                        value="{{ old('nomor_hp') }}"
                    >
                </div>

                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input
                        type="text"
                        id="nim"
                        name="nim"
                        value="{{ old('nim') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="program_studi">Program Studi</label>
                    <input
                        type="text"
                        id="program_studi"
                        name="program_studi"
                        value="{{ old('program_studi') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="fakultas">Fakultas</label>
                    <input
                        type="text"
                        id="fakultas"
                        name="fakultas"
                        value="{{ old('fakultas') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="angkatan">Angkatan</label>
                    <input
                        type="number"
                        id="angkatan"
                        name="angkatan"
                        value="{{ old('angkatan', now()->year) }}"
                        required
                    >
                </div>

                <div></div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn">
                Daftar Akun
            </button>
        </form>

        <div class="links">
            <a href="{{ route('home') }}">Kembali</a>
            <a href="{{ route('login') }}">Sudah punya akun? Login</a>
        </div>
    </main>
</body>
</html>