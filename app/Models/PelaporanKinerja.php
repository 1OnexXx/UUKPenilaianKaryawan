<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelaporanKinerja extends Model
{
    protected $table = 'pelaporan_kinerja';

    protected $fillable = [
        'karyawan_id',
        'periode',
        'target_kinerja_id',
        'target_kinerja',
        'dibuat_oleh',
        'divisi_id',    
        'skor_objektif',
        'jumlah_laporan',
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

public function targetKinerja()
{
    return $this->belongsTo(TargetKinerja::class, 'target_kinerja_id');
}


}
