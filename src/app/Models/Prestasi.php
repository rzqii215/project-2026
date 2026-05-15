<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestasi extends Model
{
    use HasFactory;

    public const STATUS_DIAJUKAN = 'diajukan';
    public const STATUS_DIVERIFIKASI = 'diverifikasi';
    public const STATUS_DITOLAK = 'ditolak';

    public const TINGKAT_KAMPUS = 'kampus';
    public const TINGKAT_KABUPATEN = 'kabupaten';
    public const TINGKAT_PROVINSI = 'provinsi';
    public const TINGKAT_NASIONAL = 'nasional';
    public const TINGKAT_INTERNASIONAL = 'internasional';

    protected $fillable = [
        'mahasiswa_id',
        'kategori_prestasi_id',
        'judul',
        'penyelenggara',
        'tingkat',
        'peringkat',
        'tanggal_prestasi',
        'deskripsi',
        'status',
        'catatan_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_prestasi' => 'date',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function kategoriPrestasi(): BelongsTo
    {
        return $this->belongsTo(KategoriPrestasi::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function berkasPrestasis(): HasMany
    {
        return $this->hasMany(BerkasPrestasi::class);
    }

    public function isDiajukan(): bool
    {
        return $this->status === self::STATUS_DIAJUKAN;
    }

    public function isDiverifikasi(): bool
    {
        return $this->status === self::STATUS_DIVERIFIKASI;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}