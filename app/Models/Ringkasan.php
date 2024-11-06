<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ringkasan extends Model
{
    use HasFactory;
    protected $table = 'ringkasan';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }
}
