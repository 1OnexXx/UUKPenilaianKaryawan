<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal_harian';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'uraian',
        'status',
        'komentar',
        'komentar_balasan', 
        'judul',
    ];
    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function lampiran()
{
    return $this->morphMany(\App\Models\Lampiran::class, 'lampiranable');
}
}
