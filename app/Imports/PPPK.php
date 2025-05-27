<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PPPK implements ToModel, WithStartRow
{
    /**
     * @param Collection $collection
     */
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function startRow(): int
    {
        return 2; // Mulai dari baris ke-3
    }
    public function model(array $row)
    {

        $param['nip'] = $row[1];
        $param['nama'] = $row[2];
        $param['jabatan'] = '-';
        $param['pangkat'] = '-';
        $param['skpd_id'] = $row[3];
        $param['status_asn'] = 'PPPK';
        $param['jenis_presensi'] = 1;
        $param['is_aktif'] = 1;

        $p = Pegawai::create($param);

        $rolePegawai = Role::where('name', 'pegawai')->first();

        $u = new User;
        $u->name = $row[2];
        $u->username = $row[1];
        $u->password = bcrypt('pppk');
        $u->save();
        $user_id = $u->id;

        $update = $p;
        $update->user_id = $user_id;
        $update->save();


        $u->roles()->attach($rolePegawai);
        $this->command->info("✔️ Berhasil import: {$row[1]} ({$row[0]})");
    }
}
