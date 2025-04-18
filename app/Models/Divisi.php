<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $fillable = [
        'nama_divisi',
        'deskripsi',
    ];
    
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function target_kinerja()
    {
        return $this->hasMany(TargetKinerja::class);
    }
    
}
