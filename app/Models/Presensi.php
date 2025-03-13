<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
    public function puskesmas()
    {
        return $this->belongsTo(Puskesmas::class, 'puskesmas_id');
    }
    public function lokasiabsenmasuk()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi_masuk', 'id');
    }
    public function jenis_keterangan()
    {
        return $this->belongsTo(JenisKeterangan::class, 'jenis_keterangan_id');
    }
}
