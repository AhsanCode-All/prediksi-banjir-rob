<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusPrediksi extends Model
{
    protected $table = 'status_prediksi';

    protected $fillable = [
        'tanggal',
        'status',
        'model_versi',
        'is_simulasi',
        'total_data',
        'pesan',
        'mulai_diproses',
        'selesai_diproses',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'is_simulasi' => 'boolean',
            'mulai_diproses' => 'datetime',
            'selesai_diproses' => 'datetime',
        ];
    }

    public function prediksi(): HasMany
    {
        return $this->hasMany(Prediksi::class);
    }
}