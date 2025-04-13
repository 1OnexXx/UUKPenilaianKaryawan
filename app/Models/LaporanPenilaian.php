<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenilaian extends Model
{
    protected $table = 'laporan_penilaian';

protected $fillable = [
    'karyawan_id',
    'dibuat_oleh',
    'jenis_laporan',
    'rata_rata_nilai',
    'rekomendasi',
    'periode',
];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
    
}
