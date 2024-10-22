<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function __construct()
    {
        // Terapkan middleware web yang sudah includes CSRF protection
        $this->middleware('web');
    }

    public function showRegistrationForm()
    {
        $levels = LevelModel::where('level_kode', '!=', 'ADM')->get();
        // Debug untuk memastikan data level dimuat
        \Log::info('Available levels:', $levels->toArray());
        return view('auth.register', compact('levels'));
    }

    public function register(Request $request)
    {
        // Tambahkan validasi token CSRF
        if (!$request->hasValidSignature() && $request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'CSRF token mismatch. Silakan muat ulang halaman.'
            ], 419);
        }

        \Log::info('Register request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:m_user,username',
            'nama' => 'required',
            'password' => 'required|min:6|confirmed',
            'level_id' => 'required|exists:m_level,level_id' // Sesuaikan dengan nama tabel dan kolom yang benar
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'nama.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'level_id.required' => 'Role wajib dipilih',
            'level_id.exists' => 'Role tidak valid'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        try {
            $user = UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => Hash::make($request->password),
                'level_id' => $request->level_id
            ]);

            auth()->login($user);

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Registrasi berhasil!',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('success', 'Registrasi berhasil!');

        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')
                ->withInput($request->except('password'));
        }
    }
}
