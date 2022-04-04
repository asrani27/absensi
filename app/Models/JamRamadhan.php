<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamRamadhan extends Model
{
    use HasFactory;
    protected $table = 'jam_ramadhan';
    protected $guarded = ['id'];
}
