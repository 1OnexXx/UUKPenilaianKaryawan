<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetKinerja extends Model
{
    protected $table = 'target_kinerja';
    protected $fillable = [
        'karyawan_id',
        'divisi_id',
        'periode',
        'judul_target',
        'target_laporan',
        'deadline',
        'dibuat_oleh',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
    
}
