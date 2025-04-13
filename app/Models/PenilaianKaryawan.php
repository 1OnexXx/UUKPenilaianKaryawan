<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianKaryawan extends Model
{
    protected $table = 'penilaian_karyawan';

    protected $fillable =[
        'karyawan_id',
        'penilai_id',
        'kategori_id',
        'nilai',
        'komentar',
        'periode',
        
    ];


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPenilaian::class, 'kategori_id', 'id');
    }   
}
