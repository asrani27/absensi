<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPPK extends Model
{
    use HasFactory;
    protected $table = 'pppk';
    protected $guarded = ['id'];
}
