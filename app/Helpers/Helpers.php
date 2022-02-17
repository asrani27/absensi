<?php

use App\Models\BulanTahun;

function bulanTahun()
{
    return BulanTahun::orderBy('id', 'DESC')->get();
}
