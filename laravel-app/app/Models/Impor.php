<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Impor extends Model
{
    protected $table = 'impor';

    protected $fillable = [
        'nama_file',
        'path_file',
        'total_data',
        'data_berhasil',
        'data_gagal',
        'status',
        'keterangan',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dataHarian(): HasMany
    {
        return $this->hasMany(DataHarian::class);
    }
}