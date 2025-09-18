<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiApel extends Model
{
    use HasFactory;
    protected $table = 'presensi_apel';
    protected $guarded = ['id'];
}
