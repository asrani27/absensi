<?php

namespace App\Policies;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CutiPolicy
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

    public function upload(User $user, Cuti $cuti)
    {
        return $user->skpd->id === $cuti->skpd_id;
    }

    public function delete(User $user, Cuti $cuti)
    {
        return $user->skpd->id === $cuti->skpd_id;
    }
}
