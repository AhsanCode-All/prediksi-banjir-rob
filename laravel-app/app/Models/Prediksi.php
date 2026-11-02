<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediksi extends Model
{
    protected $table = 'prediksi';

    protected $fillable = [
        'kelurahan_id',
        'status_prediksi_id',
        'tanggal',
        'probabilitas',
        'tingkat',
        'is_banjir',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'probabilitas' => 'decimal:5',
            'is_banjir' => 'boolean',
        ];
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function statusPrediksi(): BelongsTo
    {
        return $this->belongsTo(StatusPrediksi::class);
    }
}