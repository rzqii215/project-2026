<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa - E-Portfolio</title>

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
            max-width: 430px;
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

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        input[type="email"],
        input[type="password"] {
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

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #e4e4e7;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #064e3b;
            color: #d1fae5;
            border: 1px solid #047857;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
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
    </style>
</head>
<body>
    <main class="card">
        <div class="title">
            <h1>E-Portfolio Mahasiswa</h1>
            <p>Login akun mahasiswa</p>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.mahasiswa.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <label class="checkbox">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>

            <button type="submit" class="btn">
                Sign in
            </button>
        </form>

        <div class="links">
            <a href="{{ route('home') }}">Kembali</a>
            <a href="{{ route('register.mahasiswa') }}">Daftar akun</a>
        </div>
    </main>
</body>
</html>