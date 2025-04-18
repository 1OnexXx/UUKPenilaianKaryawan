<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPenilaian extends Model
{
    protected $table = 'kategori_penilaian';
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'tipe_penilaian',
    ];

    public function penilaian_karyawan()
    {
        return $this->hasMany(PenilaianKaryawan::class, 'kategori_id', 'id');
    }
    
}
