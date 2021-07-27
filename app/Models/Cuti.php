<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;
    
    protected $table = 'cuti';
    protected $guarded = ['id'];

    public function jenis_keterangan()
    {
        return $this->belongsTo(JenisKeterangan::class, 'jenis_keterangan_id');
    }
}
