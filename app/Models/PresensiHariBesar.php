<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiHariBesar extends Model
{
    use HasFactory;
    protected $table = 'presensi_hari_besar';
    protected $guarded = ['id'];
    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
