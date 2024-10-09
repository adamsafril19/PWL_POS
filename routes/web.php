<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/',[WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController:: class, 'index']);              // menampilkan halaman awal user
    Route::post('user/list', [UserController::class, 'list'])->name('user.list');           // menampilkan data user dalam bentuk json untuk datatables
    Route:: get('/create', [UserController:: class, 'create' ]);       // menampilkan halaman form tambah user
    Route::post('/', [UserController:: class, 'store' ]);              // menyimpan data user baru
    Route:: get('/create_ajax', [UserController:: class, 'create_ajax' ])->name('user.create_ajax');      // menampilkan halaman form tambah user
    Route::post('user/ajax', [UserController:: class, 'store_ajax' ])->name('user.store_ajax');              // menyimpan data user baru
    Route::get('/{id}', [UserController:: class, 'show' ]);            // menampilkan detail user
    Route::get('/{id}/edit', [UserController:: class, 'edit' ]);       // menampilkan halaman form edit user
    Route::put('/{id}', [UserController:: class, 'update' ]);          // menyimpan perubahan data user
    Route::get('/{id}/edit_ajax', [UserController:: class, 'edit_ajax' ]);       // menampilkan halaman form edit user
    Route::put('/{id}/update_ajax', [UserController:: class, 'update_ajax' ]);          // menyimpan perubahan data user
    Route:: delete('/{id}', [UserController:: class, 'destroy' ]); // menghapus data user
});

    Route::group(['prefix' => 'level'], function() {
        Route::get('/', [LevelController:: class, 'index']);              // menampilkan halaman awal level
        Route::post('level/list', [LevelController::class, 'list'])->name('level.list');           // menampilkan data level dalam bentuk json untuk datatables
        Route:: get('/create', [LevelController:: class, 'create' ]);       // menampilkan halaman form tambah level
        Route::post('/', [LevelController:: class, 'store' ]);              // menyimpan data level baru
        Route::get('/{id}', [LevelController:: class, 'show' ]);            // menampilkan detail level
        Route::get('/{id}/edit', [LevelController:: class, 'edit' ]);       // menampilkan halaman form edit level
        Route::put('/{id}', [LevelController:: class, 'update' ]);          // menyimpan perubahan data level
        Route:: delete('/{id}', [LevelController:: class, 'destroy' ]); // menghapus data level
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
