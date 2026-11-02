<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DataHarian;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';

    protected $fillable = [
        'kode_wilayah',
        'nama_kelurahan',
        'kecamatan',
        'elevasi',
        'jarak_pantai',
    ];

    public function dataHarian(): HasMany
    {
        return $this->hasMany(DataHarian::class);
    }

    public function prediksi(): HasMany
    {
        return $this->hasMany(Prediksi::class);
    }
}