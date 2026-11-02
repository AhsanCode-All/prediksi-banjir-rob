<?php

namespace App\Models;

use App\Models\Kelurahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataHarian extends Model
{
    protected $table = 'data_harian';

    protected $fillable = [
        'kelurahan_id',
        'tanggal',
        'curah_hujan',
        'pasang_tertinggi',
        'gelombang_tertinggi',
        'impor_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'curah_hujan' => 'decimal:2',
            'pasang_tertinggi' => 'decimal:2',
            'gelombang_tertinggi' => 'decimal:2',
        ];
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function impor(): BelongsTo
    {
        return $this->belongsTo(Impor::class);
    }
}