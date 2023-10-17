<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class ModCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'membuat akun mod';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $check = User::where('username', 'agung')->first();
        $role = Role::where('name', 'mod')->first();
        if ($check == null) {

            $new = new User;
            $new->name = "Agung";
            $new->username = "agung";
            $new->password = bcrypt('agung123');
            $new->email = 'agung@gmail.com';
            $new->save();
            $new->roles()->attach($role);
            return 'sukses';
        } else {
            return 'sudah ada';
        }
    }
}
