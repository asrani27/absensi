<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoubleData extends Model
{
    use HasFactory;
    protected $table = 'doubledata';
    protected $guarded = ['id'];
    public $timestamps = false;

}
