<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Portfolio Prestasi Mahasiswa</title>

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
            position: sticky;
            top: 0;
            z-index: 20;
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

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 700;
            border: 1px solid #d1d5db;
            color: #111827;
            background: #ffffff;
        }

        .btn-primary {
            background: #f59e0b;
            border-color: #f59e0b;
        }

        .container {
            width: min(1180px, 92%);
            margin: 0 auto;
        }

        .hero {
            padding: 76px 0 56px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 40px;
            align-items: center;
        }

        .hero-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .hero h1 {
            font-size: 52px;
            line-height: 1.08;
            margin: 0 0 18px;
            letter-spacing: -1.5px;
        }

        .hero p {
            color: #4b5563;
            line-height: 1.8;
            font-size: 17px;
            margin: 0 0 26px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .hero-card {
            background: #111827;
            color: white;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.22);
        }

        .hero-card h3 {
            margin: 0 0 18px;
            font-size: 24px;
        }

        .feature-list {
            display: grid;
            gap: 14px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.08);
            padding: 16px;
            border-radius: 16px;
        }

        .feature-item strong {
            display: block;
            margin-bottom: 6px;
        }

        .feature-item span {
            color: #d1d5db;
            font-size: 14px;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin: 20px 0 42px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .stat-card span {
            display: block;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-card strong {
            display: block;
            font-size: 34px;
        }

        .section {
            padding: 34px 0 64px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: end;
            margin-bottom: 22px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 30px;
        }

        .section-header p {
            margin: 8px 0 0;
            color: #6b7280;
        }

        .prestasi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .prestasi-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }

        .prestasi-card h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .prestasi-card p {
            margin: 6px 0;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .footer {
            padding: 28px 0;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            text-align: center;
            background: white;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .stats,
            .prestasi-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                align-items: start;
                flex-direction: column;
            }

            .hero h1 {
                font-size: 38px;
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
        <section class="hero">
            <div>
                <span class="hero-badge">Sistem E-Portfolio Prestasi Mahasiswa</span>

                <h1>Kelola, verifikasi, dan tampilkan prestasi mahasiswa secara digital.</h1>

                <p>
                    Platform ini membantu mahasiswa mengajukan prestasi, admin melakukan verifikasi,
                    dan publik melihat e-portfolio prestasi mahasiswa yang sudah tervalidasi.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('portfolio.index') }}" class="btn btn-primary">Lihat Portfolio</a>
                    <a href="{{ url('/mahasiswa') }}" class="btn">Login Mahasiswa</a>
                    <a href="{{ url('/admin') }}" class="btn">Login Admin</a>
                </div>
            </div>

            <div class="hero-card">
                <h3>Fitur Utama</h3>

                <div class="feature-list">
                    <div class="feature-item">
                        <strong>Pengajuan Prestasi</strong>
                        <span>Mahasiswa dapat menambahkan data prestasi beserta dokumen pendukung.</span>
                    </div>

                    <div class="feature-item">
                        <strong>Verifikasi Admin</strong>
                        <span>Admin melakukan validasi prestasi dan memberikan catatan jika ditolak.</span>
                    </div>

                    <div class="feature-item">
                        <strong>Portfolio Publik</strong>
                        <span>Prestasi yang sudah diverifikasi dapat ditampilkan sebagai e-portfolio publik.</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats">
            <div class="stat-card">
                <span>Total Mahasiswa</span>
                <strong>{{ $totalMahasiswa }}</strong>
            </div>

            <div class="stat-card">
                <span>Prestasi Terverifikasi</span>
                <strong>{{ $totalPrestasi }}</strong>
            </div>

            <div class="stat-card">
                <span>Kategori Prestasi</span>
                <strong>{{ $totalKategori }}</strong>
            </div>
        </section>

        <section class="section">
            <div class="section-header">
                <div>
                    <h2>Prestasi Terbaru</h2>
                    <p>Daftar prestasi mahasiswa yang sudah diverifikasi oleh admin.</p>
                </div>

                <a href="{{ route('portfolio.index') }}" class="btn">Lihat Semua</a>
            </div>

            <div class="prestasi-grid">
                @forelse ($prestasiTerbaru as $prestasi)
                    <article class="prestasi-card">
                        <span class="badge">{{ $prestasi->kategoriPrestasi?->nama ?? 'Prestasi' }}</span>

                        <h3>{{ $prestasi->judul }}</h3>

                        <p>
                            <strong>Mahasiswa:</strong>
                            {{ $prestasi->mahasiswa?->name ?? '-' }}
                        </p>

                        <p>
                            <strong>Tingkat:</strong>
                            {{ ucwords(str_replace('_', ' ', $prestasi->tingkat)) }}
                        </p>

                        <p>
                            <strong>Penyelenggara:</strong>
                            {{ $prestasi->penyelenggara ?? '-' }}
                        </p>

                        @if ($prestasi->mahasiswa)
                            <p>
                                <a href="{{ route('eportfolio.show', $prestasi->mahasiswa) }}">
                                    Lihat Portfolio →
                                </a>
                            </p>
                        @endif
                    </article>
                @empty
                    <article class="prestasi-card">
                        <h3>Belum ada prestasi</h3>
                        <p>Belum ada prestasi mahasiswa yang diverifikasi.</p>
                    </article>
                @endforelse
            </div>
        </section>
    </main>

    <footer class="footer">
        E-Portfolio Prestasi Mahasiswa - {{ config('app.name') }}
    </footer>
</body>
</html>