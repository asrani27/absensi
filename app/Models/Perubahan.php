<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perubahan extends Model
{
    use HasFactory;
    protected $table = 'perubahan';
    protected $guarded = ['id'];
}
