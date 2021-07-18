<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKeterangan extends Model
{
    use HasFactory;
    
    protected $table = 'jenis_keterangan';
    protected $guarded = ['id'];
}
