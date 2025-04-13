<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $fillable = [
        'nip',
        'email',
        'no_hp',
        'tanggal_masuk',
        'jabatan',
        'divisi_id',
        'user_id',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class , 'divisi_id' , 'id');
    }   

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function jurnal()
    {
        return $this->hasMany(Jurnal::class , 'karyawan_id' , 'id');
    }

    public function pelaporan_kinerja()
    {
        return $this->hasMany(PelaporanKinerja::class , 'karyawan_id' , 'id');
    }

    public function penilaian_karyawan()
    {
        return $this->hasMany(PenilaianKaryawan::class , 'karyawan_id' , 'id');
    }

    public function penilaian()
    {
        return $this->hasMany(LaporanPenilaian::class , 'karyawan_id' , 'id');
    }

}
