<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Portfolio Mahasiswa</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #111827;
        }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .nav-container {
            width: min(1180px, 92%);
            margin: 0 auto;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
            text-decoration: none;
            color: #111827;
        }

        .nav-links {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }

        .container {
            width: min(1180px, 92%);
            margin: 0 auto;
            padding: 38px 0 64px;
        }

        .header {
            background: #111827;
            color: white;
            border-radius: 24px;
            padding: 34px;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 10px;
            font-size: 36px;
        }

        .header p {
            margin: 0;
            color: #d1d5db;
            line-height: 1.7;
        }

        .search-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .search-form {
            display: flex;
            gap: 12px;
        }

        .search-form input {
            flex: 1;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 13px 14px;
            font-size: 15px;
        }

        .btn {
            border: 0;
            border-radius: 12px;
            padding: 13px 18px;
            background: #f59e0b;
            color: #111827;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            background: #f59e0b;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .card p {
            margin: 6px 0;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            margin-top: 12px;
            margin-bottom: 16px;
            background: #ecfdf5;
            color: #047857;
            padding: 7px 11px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
        }

        .empty {
            background: white;
            border: 1px dashed #d1d5db;
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            color: #6b7280;
        }

        .pagination {
            margin-top: 24px;
        }

        .footer {
            padding: 28px 0;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            text-align: center;
            background: white;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .search-form {
                flex-direction: column;
            }

            .nav-container {
                height: auto;
                padding: 16px 0;
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
            }

            .nav-links {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="brand">E-Portfolio Mahasiswa</a>

            <div class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('portfolio.index') }}">Portfolio</a>
                <a href="{{ url('/mahasiswa') }}">Mahasiswa</a>
                <a href="{{ url('/admin') }}">Admin</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <section class="header">
            <h1>Direktori E-Portfolio Mahasiswa</h1>
            <p>
                Cari dan lihat e-portfolio mahasiswa berdasarkan nama, NIM, program studi,
                atau fakultas. Hanya mahasiswa dengan prestasi terverifikasi yang ditampilkan.
            </p>
        </section>

        <section class="search-card">
            <form method="GET" action="{{ route('portfolio.index') }}" class="search-form">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama, NIM, program studi, atau fakultas..."
                >

                <button type="submit" class="btn">Cari</button>

                @if ($search)
                    <a href="{{ route('portfolio.index') }}" class="btn" style="background: #e5e7eb;">
                        Reset
                    </a>
                @endif
            </form>
        </section>

        @if ($mahasiswas->count())
            <section class="grid">
                @foreach ($mahasiswas as $mahasiswa)
                    <article class="card">
                        <div class="avatar">
                            @if ($mahasiswa->profilMahasiswa?->foto)
                                <img src="{{ asset('storage/' . $mahasiswa->profilMahasiswa->foto) }}" alt="{{ $mahasiswa->name }}">
                            @else
                                {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                            @endif
                        </div>

                        <h3>{{ $mahasiswa->name }}</h3>

                        <p>
                            <strong>NIM:</strong>
                            {{ $mahasiswa->profilMahasiswa?->nim ?? '-' }}
                        </p>

                        <p>
                            <strong>Program Studi:</strong>
                            {{ $mahasiswa->profilMahasiswa?->program_studi ?? '-' }}
                        </p>

                        <p>
                            <strong>Fakultas:</strong>
                            {{ $mahasiswa->profilMahasiswa?->fakultas ?? '-' }}
                        </p>

                        <span class="badge">
                            {{ $mahasiswa->total_prestasi_terverifikasi }} Prestasi Terverifikasi
                        </span>

                        <div>
                            <a href="{{ route('eportfolio.show', $mahasiswa) }}" class="btn">
                                Lihat Portfolio
                            </a>
                        </div>
                    </article>
                @endforeach
            </section>

            <div class="pagination">
                {{ $mahasiswas->links() }}
            </div>
        @else
            <div class="empty">
                Tidak ada mahasiswa dengan prestasi terverifikasi.
            </div>
        @endif
    </main>

    <footer class="footer">
        E-Portfolio Prestasi Mahasiswa - {{ config('app.name') }}
    </footer>
</body>
</html>