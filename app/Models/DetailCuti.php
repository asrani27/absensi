<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCuti extends Model
{
    use HasFactory;
    protected $table = 'detail_cuti';
    protected $guarded = ['id'];
    
    public function jenis_keterangan()
    {
        return $this->belongsTo(JenisKeterangan::class, 'jenis_keterangan_id');
    }
    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
