<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Portfolio {{ $user->name }}</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #111827;
        }

        .container {
            width: min(1100px, 92%);
            margin: 0 auto;
            padding: 32px 0;
        }

        .hero {
            background: linear-gradient(135deg, #111827, #374151);
            color: white;
            border-radius: 24px;
            padding: 36px;
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 24px;
            align-items: center;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.16);
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 999px;
            background: #f59e0b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            font-weight: bold;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: 34px;
        }

        .hero p {
            margin: 4px 0;
            color: #e5e7eb;
        }

        .actions {
            margin-top: 24px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: bold;
            border: 1px solid #d1d5db;
            background: white;
            color: #111827;
        }

        .btn-primary {
            background: #f59e0b;
            border-color: #f59e0b;
            color: #111827;
        }

        .section {
            margin-top: 28px;
            background: white;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .section h2 {
            margin: 0 0 20px;
            font-size: 24px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .info-box {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            background: #f9fafb;
        }

        .info-box span {
            display: block;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .info-box strong {
            display: block;
            font-size: 16px;
        }

        .prestasi-list {
            display: grid;
            gap: 18px;
        }

        .prestasi-card {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 20px;
            background: #ffffff;
        }

        .prestasi-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
        }

        .prestasi-title {
            margin: 0;
            font-size: 20px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
            background: #fef3c7;
            color: #92400e;
            white-space: nowrap;
        }

        .meta {
            margin-top: 10px;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.7;
        }

        .description {
            margin-top: 12px;
            line-height: 1.7;
            color: #374151;
        }

        .files {
            margin-top: 14px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .file-link {
            color: #2563eb;
            font-size: 14px;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 10px;
            background: #eff6ff;
        }

        .empty {
            padding: 24px;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 16px;
            color: #6b7280;
            text-align: center;
        }

        .footer {
            margin-top: 28px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .hero {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .avatar {
                margin: 0 auto;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .prestasi-header {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <section class="hero">
            <div class="avatar">
                @if ($profil?->foto)
                    <img src="{{ asset('storage/' . $profil->foto) }}" alt="{{ $user->name }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>

            <div>
                <h1>{{ $user->name }}</h1>
                <p>E-Portfolio Prestasi Mahasiswa</p>
                <p>
                    {{ $profil?->program_studi ?? 'Program studi belum diisi' }}
                    {{ $profil?->fakultas ? ' - ' . $profil->fakultas : '' }}
                </p>

                <div class="actions">
                    <a href="{{ route('eportfolio.print', $user) }}" class="btn btn-primary" target="_blank">
                        Cetak Portfolio
                    </a>

                    <a href="{{ route('eportfolio.show', $user) }}" class="btn">
                        Link Publik
                    </a>
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Profil Mahasiswa</h2>

            <div class="profile-grid">
                <div class="info-box">
                    <span>NIM</span>
                    <strong>{{ $profil?->nim ?? '-' }}</strong>
                </div>

                <div class="info-box">
                    <span>Program Studi</span>
                    <strong>{{ $profil?->program_studi ?? '-' }}</strong>
                </div>

                <div class="info-box">
                    <span>Fakultas</span>
                    <strong>{{ $profil?->fakultas ?? '-' }}</strong>
                </div>

                <div class="info-box">
                    <span>Angkatan</span>
                    <strong>{{ $profil?->angkatan ?? '-' }}</strong>
                </div>
            </div>

            @if ($profil?->alamat)
                <div class="info-box" style="margin-top: 16px;">
                    <span>Alamat</span>
                    <strong>{{ $profil->alamat }}</strong>
                </div>
            @endif
        </section>

        <section class="section">
            <h2>Daftar Prestasi Terverifikasi</h2>

            <div class="prestasi-list">
                @forelse ($prestasis as $prestasi)
                    <article class="prestasi-card">
                        <div class="prestasi-header">
                            <div>
                                <h3 class="prestasi-title">{{ $prestasi->judul }}</h3>

                                <div class="meta">
                                    <div><strong>Kategori:</strong> {{ $prestasi->kategoriPrestasi?->nama ?? '-' }}</div>
                                    <div><strong>Penyelenggara:</strong> {{ $prestasi->penyelenggara ?? '-' }}</div>
                                    <div><strong>Peringkat/Capaian:</strong> {{ $prestasi->peringkat ?? '-' }}</div>
                                    <div>
                                        <strong>Tanggal:</strong>
                                        {{ $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>

                            <span class="badge">
                                {{ ucwords(str_replace('_', ' ', $prestasi->tingkat)) }}
                            </span>
                        </div>

                        @if ($prestasi->deskripsi)
                            <p class="description">{{ $prestasi->deskripsi }}</p>
                        @endif

                        @if ($prestasi->berkasPrestasis->isNotEmpty())
                            <div class="files">
                                @foreach ($prestasi->berkasPrestasis as $berkas)
                                    <a href="{{ asset('storage/' . $berkas->path_file) }}" class="file-link" target="_blank">
                                        {{ $berkas->nama_file }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="empty">
                        Belum ada prestasi yang sudah diverifikasi.
                    </div>
                @endforelse
            </div>
        </section>

        <footer class="footer">
            E-Portfolio Prestasi Mahasiswa - {{ config('app.name') }}
        </footer>
    </main>
</body>
</html>