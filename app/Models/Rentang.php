<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rentang extends Model
{
    use HasFactory;
    protected $table = 'rentang';
    protected $guarded = ['id'];
}
