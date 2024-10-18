<?php

use App\Models\UserModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);
// Route::get('/user', [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}',[UserController :: class, 'ubah_simpan']);
// Route::get('/user/hapus/{id}', [UserController :: class, 'hapus']);
// Route::get('/',[WelcomeController::class, 'index']);



Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController:: class, 'index']);              // menampilkan halaman awal user
    Route::post('user/list', [UserController::class, 'list'])->name('user.list');           // menampilkan data user dalam bentuk json untuk datatables
    Route:: get('/create', [UserController:: class, 'create' ]);       // menampilkan halaman form tambah user
    Route::post('/', [UserController:: class, 'store' ]);              // menyimpan data user baru
    Route:: get('/create_ajax', [UserController:: class, 'create_ajax' ])->name('user.create_ajax');      // menampilkan halaman form tambah user
    Route::post('user/ajax', [UserController:: class, 'store_ajax' ])->name('user.store_ajax');              // menyimpan data user baru
    Route::get('/{id}', [UserController:: class, 'show' ]);            // menampilkan detail user
    Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax'])->name('user.show.ajax');
    Route::get('/{id}/edit', [UserController:: class, 'edit' ]);       // menampilkan halaman form edit user
    Route::put('/{id}', [UserController:: class, 'update' ]);          // menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController:: class, 'edit_ajax' ]);       // menampilkan halaman form edit user
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);          // menyimpan perubahan data user
    Route:: get('/{id}/delete_ajax', [UserController:: class, 'confirm_ajax' ]); // confirm delete data user
    Route:: delete('/{id}/delete_ajax', [UserController:: class, 'delete_ajax' ]); // menghapus data user
    Route:: delete('/{id}', [UserController:: class, 'destroy' ]); // menghapus data user
});

    Route::group(['prefix' => 'kategori'], function() {
        Route::get('/', [KategoriController:: class, 'index']);              // menampilkan halaman awal level
        Route::post('/kategori/list', [KategoriController::class, 'list'])->name('kategori.list');           // menampilkan data level dalam bentuk json untuk datatables
        Route:: get('/create', [KategoriController:: class, 'create' ]);       // menampilkan halaman form tambah level
        Route::post('/', [KategoriController:: class, 'store' ]);              // menyimpan data level baru
        Route::get('/{id}', [KategoriController:: class, 'show' ]);            // menampilkan detail level
        Route::get('/{id}/edit', [KategoriController:: class, 'edit' ]);       // menampilkan halaman form edit level
        Route::put('/{id}', [KategoriController:: class, 'update' ]);          // menyimpan perubahan data level
        Route:: delete('/{id}', [KategoriController:: class, 'destroy' ]); // menghapus data level
    });

    Route::group(['prefix' => 'barang'], function() {
        Route::get('/', [BarangController:: class, 'index']);              // menampilkan halaman awal level
        Route::post('/barang/list', [BarangController::class, 'list'])->name('barang.list');           // menampilkan data level dalam bentuk json untuk datatables
        Route:: get('/create', [BarangController:: class, 'create' ]);       // menampilkan halaman form tambah level
        Route::post('/', [BarangController:: class, 'store' ]);              // menyimpan data level baru
        Route::get('/{id}', [BarangController:: class, 'show' ]);            // menampilkan detail level
        Route::get('/{id}/edit', [BarangController:: class, 'edit' ]);       // menampilkan halaman form edit level
        Route::put('/{id}', [BarangController:: class, 'update' ]);          // menyimpan perubahan data level
        Route:: delete('/{id}', [BarangController:: class, 'destroy' ]); // menghapus data level
    });

    Route::group(['prefix' => 'supplier'], function() {
        Route::get('/', [SupplierController:: class, 'index']);              // menampilkan halaman awal level
        Route::post('/supplier/list', [SupplierController::class, 'list'])->name('supplier.list');           // menampilkan data level dalam bentuk json untuk datatables
        Route:: get('/create', [SupplierController:: class, 'create' ]);       // menampilkan halaman form tambah level
        Route::post('/', [SupplierController:: class, 'store' ]);              // menyimpan data level baru
        Route::get('/{id}', [SupplierController:: class, 'show' ]);            // menampilkan detail level
        Route::get('/{id}/edit', [SupplierController:: class, 'edit' ]);       // menampilkan halaman form edit level
        Route::put('/{id}', [SupplierController:: class, 'update' ]);          // menyimpan perubahan data level
        Route:: delete('/{id}', [SupplierController:: class, 'destroy' ]); // menghapus data level
    });

    Route:: pattern ('id' , '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postlogin'])->name('login.post');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    Route::middleware(['auth'])->group(function() {
        Route::get('/', [WelcomeController::class, 'index'])->name('home');
        Route::middleware( ['authorize: ADM']) ->group (function() {
            Route::prefix('level')->group(function() {
                Route::get('/', [LevelController:: class, 'index']);              // menampilkan halaman awal level
                Route::post('level/list', [LevelController::class, 'list'])->name('level.list');           // menampilkan data level dalam bentuk json untuk datatables
                Route:: get('/create', [LevelController:: class, 'create' ]);       // menampilkan halaman form tambah level
                Route::post('/', [LevelController:: class, 'store' ]);              // menyimpan data level baru
                Route:: get('/create_ajax', [LevelController:: class, 'create_ajax' ])->name('level.create_ajax');       // menampilkan halaman form tambah level
                Route::post('/ajax', [LevelController:: class, 'store_ajax' ])->name('level.store_ajax');              // menyimpan data level baru
                Route::get('/{id}', [LevelController:: class, 'show' ]);            // menampilkan detail level
                Route::get('/{id}', [LevelController:: class, 'show_ajax' ]);            // menampilkan detail level
                Route::get('/{id}/edit', [LevelController:: class, 'edit' ]);       // menampilkan halaman form edit level
                Route::get('/{id}/edit_ajax', [LevelController:: class, 'edit_ajax' ]);       // menampilkan halaman form edit level
                Route::put('/{id}', [LevelController:: class, 'update' ]);          // menyimpan perubahan data level
                Route:: delete('/{id}', [LevelController:: class, 'destroy' ]); // menghapus data level
            });
        });
    });
    Route::get('/check-user/{username}', function($username) {
        $user = \App\Models\UserModel::where('username', $username)->first();
        if ($user) {
            return response()->json([
                'status' => true,
                'user' => $user->toArray()
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
    });




