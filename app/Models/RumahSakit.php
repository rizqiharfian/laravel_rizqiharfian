<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumahSakit extends Model
{
    protected $fillable = ['nama_rumah_sakit', 'alamat', 'email', 'telepon'];

    public function pasiens()
    {
        return $this->hasMany(Pasien::class);
    }
}