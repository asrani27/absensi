<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulanTahun extends Model
{
    use HasFactory;
    protected $table = 'bulan_tahun';
    protected $guarded = ['id'];
    public $timestamp = false;
}
