<?php

namespace App\Models;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skpd extends Model
{
    use HasFactory;
    protected $table = 'skpd';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'skpd_id');
    }

    public function Lokasi()
    {
        return $this->hasMany(Lokasi::class, 'skpd_id');
    }
}
