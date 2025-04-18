<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelaporanKinerja extends Model
{
    protected $table = 'pelaporan_kinerja';

    protected $fillable = [
        'karyawan_id',
        'periode',
        'isi_laporan',
        'status',
    ];

    public function karyawan(){
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function lampiran2()
{
    return $this->morphMany(\App\Models\Lampiran::class, 'lampiranable');
}

}
