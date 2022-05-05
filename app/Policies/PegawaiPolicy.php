<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Auth\Access\HandlesAuthorization;

class PegawaiPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Pegawai $pegawai)
    {
        return $user->skpd->id === $pegawai->skpd_id;
    }
}
