<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak E-Portfolio {{ $user->name }}</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            color: #111827;
            background: #f3f4f6;
        }

        .actions {
            width: 210mm;
            margin: 20px auto;
            text-align: right;
        }

        .print-btn {
            border: 0;
            background: #111827;
            color: white;
            padding: 12px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 24px;
            padding: 20mm;
            background: white;
        }

        .header {
            border-bottom: 3px solid #111827;
            padding-bottom: 16px;
            display: flex;
            justify-content: space-between;
            gap: 24px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .subtitle {
            margin: 0;
            color: #4b5563;
            font-size: 14px;
        }

        .profile {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .box {
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 8px;
        }

        .box span {
            display: block;
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .box strong {
            font-size: 14px;
        }

        .section-title {
            margin-top: 28px;
            margin-bottom: 14px;
            font-size: 20px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 8px;
        }

        .prestasi {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .prestasi h3 {
            margin: 0 0 8px;
            font-size: 17px;
        }

        .meta {
            color: #374151;
            font-size: 13px;
            line-height: 1.6;
        }

        .description {
            margin-top: 8px;
            font-size: 13px;
            line-height: 1.6;
        }

        .empty {
            padding: 16px;
            border: 1px dashed #9ca3af;
            color: #6b7280;
            text-align: center;
        }

        @media print {
            body {
                background: white;
            }

            .actions {
                display: none;
            }

            .page {
                margin: 0;
                width: auto;
                min-height: auto;
                padding: 12mm;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <main class="page">
        <section class="header">
            <div>
                <h1>{{ $user->name }}</h1>
                <p class="subtitle">E-Portfolio Prestasi Mahasiswa</p>
                <p class="subtitle">{{ config('app.name') }}</p>
            </div>

            <div>
                <p class="subtitle">Tanggal Cetak:</p>
                <strong>{{ now()->format('d M Y H:i') }}</strong>
            </div>
        </section>

        <section class="profile">
            <div class="box">
                <span>NIM</span>
                <strong>{{ $profil?->nim ?? '-' }}</strong>
            </div>

            <div class="box">
                <span>Email</span>
                <strong>{{ $user->email }}</strong>
            </div>

            <div class="box">
                <span>Program Studi</span>
                <strong>{{ $profil?->program_studi ?? '-' }}</strong>
            </div>

            <div class="box">
                <span>Fakultas</span>
                <strong>{{ $profil?->fakultas ?? '-' }}</strong>
            </div>

            <div class="box">
                <span>Angkatan</span>
                <strong>{{ $profil?->angkatan ?? '-' }}</strong>
            </div>

            <div class="box">
                <span>Total Prestasi Terverifikasi</span>
                <strong>{{ $prestasis->count() }}</strong>
            </div>
        </section>

        <h2 class="section-title">Prestasi Terverifikasi</h2>

        @forelse ($prestasis as $prestasi)
            <article class="prestasi">
                <h3>{{ $prestasi->judul }}</h3>

                <div class="meta">
                    <div><strong>Kategori:</strong> {{ $prestasi->kategoriPrestasi?->nama ?? '-' }}</div>
                    <div><strong>Tingkat:</strong> {{ ucwords(str_replace('_', ' ', $prestasi->tingkat)) }}</div>
                    <div><strong>Penyelenggara:</strong> {{ $prestasi->penyelenggara ?? '-' }}</div>
                    <div><strong>Peringkat/Capaian:</strong> {{ $prestasi->peringkat ?? '-' }}</div>
                    <div>
                        <strong>Tanggal Prestasi:</strong>
                        {{ $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d M Y') : '-' }}
                    </div>
                </div>

                @if ($prestasi->deskripsi)
                    <p class="description">{{ $prestasi->deskripsi }}</p>
                @endif
            </article>
        @empty
            <div class="empty">
                Belum ada prestasi yang sudah diverifikasi.
            </div>
        @endforelse
    </main>
</body>
</html>