<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function index(){

       // tambah data user dengan Eloquent Model
        // $data = [
        //     'username' => 'customer-1',
        //     'nama' => 'Pelanggan',
        //     'password' => Hash :: make('12345'),
        //     'level_id' => 4
        // ];
        //     UserModel :: insert($data); // tambahkan data ke tabel m_user

        // $data = [
        //     'nama' => 'Pelanggan Pertama'
        // ];
        //     UserModel :: where('username', 'customer-1')->update($data);

        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345'),
        // ];
        // UserModel::create($data);

        // // coba akses mode usermodel
        // $user = UserModel::all(); // ambil semua data dari tabel m_user
        // return view('user', ['data' => $user]);

        // $user = UserModel::find(1); // ambil model dengan kunci utama
        // return view('user', ['data' => $user]);

        // $user = UserModel::where('level_id', 1)-> first(); // ambil model yang pertama dari batasan yang ditentukan
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstwhere('level_id', 1); // ambil model yang pertama dari batasan yang ditentukan
        // return view('user', ['data' => $user]);

        // $user = UserModel::findOr(20,['username', 'nama'], function(){
        //     abort(404);
        // });
        // return view('user', ['data' => $user]);

        // $user = UserModel::findOrFail(1);
        // return view('user', ['data' => $user]);

        $user = UserModel::where('username', 'manager9')->firstOrFail();
        return view('user', ['data' => $user]);
    }
    

}
