<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPegawai extends Model
{
    use HasFactory;
    protected $table = 'lokasi_pegawai';
    protected $guarded = ['id'];
}
