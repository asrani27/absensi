<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class LokasiPolicy
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

    public function edit(User $user, Lokasi $lokasi)
    {
        return $user->skpd->id === $lokasi->skpd_id;
    }

    public function update(User $user, Lokasi $lokasi)
    {
        return $user->skpd->id === $lokasi->skpd_id;
    }

    public function delete(User $user, Lokasi $lokasi)
    {
        return $user->skpd->id === $lokasi->skpd_id;
    }
}
