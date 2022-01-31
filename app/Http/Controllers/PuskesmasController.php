<?php

namespace App\Http\Controllers;

use App\Models\Puskesmas;
use Illuminate\Http\Request;

class PuskesmasController extends Controller
{
    public function index()
    {
        $data = Puskesmas::get();
        return view('admin.puskesmas.index', compact('data'));
    }

    public function createuser($id)
    {
        $username = '1.02.01.' . $id;
        $check = User::where('username', $username)->first();
        if ($check == null) {
            $role = Role::where('name', 'puskesmas')->first();
            $n = new User;
            $n->name = Puskesmas::find($id)->nama;
            $n->username = $username;
            $n->password = bcrypt('admin495');
            $n->save();

            $n->roles()->attach($role);

            $check->update(['user_id' => $n->id]);

            toastr()->success('Berhasil Di buat, password : admin495');
            return back();
        } else {
            toastr()->success('Username sudah ada');
            return back();
        }
    }

    public function sync()
    {
        toastr()->success('Sinkronisasi berhasil');
        return back();
    }
}
