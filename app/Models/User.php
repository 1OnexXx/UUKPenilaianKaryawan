<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function karyawan()
{
    return $this->hasOne(Karyawan::class);
}

    public function penilaian_karyawan()
    {
        return $this->hasMany(PenilaianKaryawan::class, 'penilai_id', 'id');
    }

    public function laporan_penilaian()
    {
        return $this->hasMany(LaporanPenilaian::class, 'penilai_id', 'id');
    }

    public function detail()
{
    return $this->hasOne(Karyawan::class, 'user_id');
}

}
