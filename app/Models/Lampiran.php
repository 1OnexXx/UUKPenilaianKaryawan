<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'lapiran';
    protected $fillable = ['file_path', 'file_type'];

    public function lampiranable()
    {
        return $this->morphTo();
    }
}
