<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BerkasPrestasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestasi_id',
        'nama_file',
        'path_file',
        'tipe_file',
    ];

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class);
    }
}