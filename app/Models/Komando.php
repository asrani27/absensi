<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komando extends Model
{
    use HasFactory;
    protected $table = 'command';
    protected $guarded = ['id'];
    public $timestamps = false;
}
