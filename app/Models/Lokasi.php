<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'lokasi_id');
    }

    public function pegawailokasi()
    {
        return $this->belongsToMany(Pegawai::class, 'lokasi_pegawai', 'lokasi_id', 'pegawai_id');
    }
}
