<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Portfolio</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f8fafc;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
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
            color: #6b7280;
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
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #ffffff;
            color: #111827;
            padding: 13px 14px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #d97706;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
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
            background: #d97706;
            color: #ffffff;
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
            color: #d97706;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="title">
            <h1>E-Portfolio Admin</h1>
            <p>Login manual admin</p>
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.admin.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', 'admin@admin.com') }}"
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

            <button type="submit" class="btn">
                Sign in
            </button>
        </form>

        <div class="links">
            <a href="{{ route('home') }}">Kembali</a>
            <a href="{{ url('/login-mahasiswa') }}">Login Mahasiswa</a>
        </div>
    </main>
</body>
</html>