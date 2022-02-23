<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunci extends Model
{
    use HasFactory;
    protected $table = 'kunci';
    protected $guarded = ['id'];
}
