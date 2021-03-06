<?php

namespace App\Models;

use App\Models\Puskesmas;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->count() == 1;
    }

    public function skpd()
    {
        return $this->hasOne(Skpd::class, 'user_id');
    }

    public function puskesmas()
    {
        return $this->hasOne(Puskesmas::class, 'user_id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'user_id');
    }
}
