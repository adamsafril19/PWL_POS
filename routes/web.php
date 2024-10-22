<?php

use App\Models\UserModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PenjualanController;

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


    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route:: pattern ('id' , '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postlogin'])->name('login.post');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    Route::group(['middleware' => ['web']], function () {
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::get('/profile/change-photo', [UserController::class, 'showChangePhotoForm']);
        Route::post('/profile/update-photo', [UserController::class, 'updatePhoto']);
        Route::get('/profile/manage', [UserController::class, 'showManageProfileForm']);
        Route::post('/profile/update', [UserController::class, 'updateProfile']);
    });

    Route::middleware(['auth'])->group(function() {
        Route::get('/dashboard', [WelcomeController::class, 'index'])->name('dashboard');
        Route::middleware( ['authorize: ADM']) ->group (function() {
            Route::prefix('level')->group(function() {
                Route::get('/', [LevelController:: class, 'index']);              // menampilkan halaman awal level
                Route::post('level/list', [LevelController::class, 'list'])->name('level.list');           // menampilkan data level dalam bentuk json untuk datatables
                Route:: get('/create', [LevelController:: class, 'create' ]);       // menampilkan halaman form tambah level
                Route::post('/', [LevelController:: class, 'store' ]);              // menyimpan data level baru
                Route:: get('/create_ajax', [LevelController:: class, 'create_ajax' ])->name('level.create_ajax');       // menampilkan halaman form tambah level
                Route::post('/ajax', [LevelController:: class, 'store_ajax' ])->name('level.store_ajax');              // menyimpan data level baru
                Route::get('/{id}', [LevelController:: class, 'show' ]);            // menampilkan detail level
                Route::get('/{id}/show_ajax', [LevelController:: class, 'show_ajax' ]);            // menampilkan detail level
                Route::get('/{id}/edit', [LevelController:: class, 'edit' ]);       // menampilkan halaman form edit level
                Route::get('/{id}/edit_ajax', [LevelController:: class, 'edit_ajax' ]);       // menampilkan halaman form edit level
                Route::put('/{id}', [LevelController:: class, 'update' ]);          // menyimpan perubahan data level
                Route::put('/{id}/update_ajax', [LevelController:: class, 'update_ajax' ]);          // menyimpan perubahan data level
                Route:: delete('/{id}', [LevelController:: class, 'destroy' ]); // menghapus data level
                Route:: get('/{id}/delete_ajax', [LevelController:: class, 'confirm_ajax' ]); // confirm delete data user
                Route:: delete('/{id}/delete_ajax', [LevelController:: class, 'delete_ajax' ]); // menghapus data user
                Route::get('/export_excel', [LevelController::class, 'export_excel']);
                Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
                Route::get('/import', [LevelController::class, 'import']);
                Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
                Route::get('/template_excel', [LevelController::class, 'template_excel'])->name('level.template_excel');
            });
        });



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
            Route::get('/{id}/delete_ajax', [UserController:: class, 'confirm_ajax' ]); // confirm delete data user
            Route::delete('/{id}/delete_ajax', [UserController:: class, 'delete_ajax' ]); // confirm delete data user
            Route::delete('/{id}', [UserController:: class, 'destroy' ]); // menghapus data user
            Route::get('/import', [UserController::class, 'import']);
            Route::post('/import_ajax', [UserController::class, 'import_ajax']);
            Route::get('/download_template', [UserController::class, 'download_template']);
            Route::get('/export_excel', [UserController::class, 'export_excel']);
            Route::get('/export_pdf', [UserController::class, 'export_pdf']);
        });

        Route::group(['prefix' => 'kategori'], function() {
            Route::get('/', [KategoriController:: class, 'index']);              // menampilkan halaman awal level
            Route::post('/kategori/list', [KategoriController::class, 'list'])->name('kategori.list');           // menampilkan data level dalam bentuk json untuk datatables
            Route:: get('/create', [KategoriController:: class, 'create' ]);       // menampilkan halaman form tambah level
            Route:: get('/create_ajax', [KategoriController:: class, 'create_ajax' ])->name('kategori.create.ajax');       // menampilkan halaman form tambah level
            Route::post('/', [KategoriController:: class, 'store' ]);              // menyimpan data level baru
            Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax'])->name('kategori.store.ajax');              // menyimpan data level baru
            Route::get('/{id}', [KategoriController:: class, 'show' ]);            // menampilkan detail level
            Route::get('/{id}/show_ajax', [KategoriController:: class, 'show_ajax' ]);            // menampilkan detail level
            Route::get('/{id}/edit', [KategoriController:: class, 'edit' ]);       // menampilkan halaman form edit level
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax'])->name('kategori.edit.ajax');
            Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax'])->name('kategori.update.ajax');       // menampilkan halaman form edit level
            Route::put('/{id}', [KategoriController:: class, 'update' ]);          // menyimpan perubahan data level
            Route::put('/{id}/update_ajax', [KategoriController:: class, 'update_ajax' ]);          // menyimpan perubahan data level
            Route::delete('/{id}', [KategoriController:: class, 'destroy' ]); // menghapus data level
            Route::delete('/kategori/{id}/delete_ajax', 'KategoriController@delete_ajax')->name('kategori.delete');
            Route::get('/{id}/delete_ajax', [KategoriController:: class, 'confirm_ajax' ]); // menghapus data level
            Route::get('/import', [KategoriController::class, 'import']);
            Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
            Route::get('/download_template', [KategoriController::class, 'download_template']);
            Route::get('/export_excel', [KategoriController::class, 'export_excel']);
            Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);
        });

        Route::group(['prefix' => 'barang'], function() {
            Route::get('/', [BarangController:: class, 'index']);              // menampilkan halaman awal level
            Route::post('/barang/list', [BarangController::class, 'list'])->name('barang.list');           // menampilkan data level dalam bentuk json untuk datatables
            Route:: get('/create', [BarangController:: class, 'create' ]);       // menampilkan halaman form tambah level
            Route:: get('/create_ajax', [BarangController:: class, 'create_ajax' ]);       // menampilkan halaman form tambah level
            Route::post('/', [BarangController:: class, 'store' ]);              // menyimpan data level baru
            Route::post('barang/ajax', [BarangController:: class, 'store_ajax' ])->name('barang.store.ajax');              // menyimpan data level baru
            Route::get('/{id}', [BarangController:: class, 'show' ]);            // menampilkan detail level
            Route::get('/{id}/show_ajax', [BarangController:: class, 'show_ajax' ]);            // menampilkan detail level
            Route::get('/{id}/edit', [BarangController:: class, 'edit' ]);       // menampilkan halaman form edit level
            Route::get('/{id}/edit_ajax', [BarangController:: class, 'edit_ajax' ]);       // menampilkan halaman form edit level
            Route::put('/{id}', [BarangController:: class, 'update' ]);          // menyimpan perubahan data level
            Route::put('/{id}/update_ajax', [BarangController:: class, 'update_ajax' ]);          // menyimpan perubahan data level
            Route::delete('/{id}', [BarangController:: class, 'delete' ]); // menghapus data level
            Route::delete('/{id}/delete_ajax', [BarangController:: class, 'delete_ajax' ]); // menghapus data level
            Route::get('/{id}/delete_ajax', [BarangController:: class, 'confirm_ajax' ]); // menghapus data level
            Route:: get('/import' , [BarangController:: class,'import' ]); // ajax form upload excel
            Route:: post('/barang/import_ajax', [BarangController:: class, 'import_ajax' ]); // ajax import excel
            Route::get('/export_excel', [BarangController::class, 'export_excel'])->name('barang.export_excel');
            Route::get('/export_pdf', [BarangController::class, 'export_pdf'])->name('barang.export_pdf');
        });

        Route::group(['prefix' => 'supplier'], function() {
            Route::get('/', [SupplierController:: class, 'index']);              // menampilkan halaman awal level
            Route::post('/supplier/list', [SupplierController::class, 'list'])->name('supplier.list');           // menampilkan data level dalam bentuk json untuk datatables
            Route:: get('/create', [SupplierController:: class, 'create' ]);       // menampilkan halaman form tambah level
            Route:: get('/create_ajax', [SupplierController:: class, 'create_ajax' ]);       // menampilkan halaman form tambah level
            Route::post('/', [SupplierController:: class, 'store' ]);              // menyimpan data level baru
            Route::post('/supplier/ajax', [SupplierController:: class, 'store_ajax' ])->name('supplier.store_ajax');              // menyimpan data level baru
            Route::get('/{id}', [SupplierController:: class, 'show' ]);            // menampilkan detail level
            Route::get('/{id}/edit', [SupplierController:: class, 'edit' ]);       // menampilkan halaman form edit level
            Route::put('/{id}', [SupplierController:: class, 'update' ]);          // menyimpan perubahan data level
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax'])->name('supplier.update.ajax');  // menyimpan perubahan data level
            Route::delete('/{id}', [SupplierController:: class, 'destroy' ]); // menghapus data level
            Route::get('/{id}/show_ajax', [SupplierController:: class, 'show_ajax' ]);            // menampilkan detail level
            Route::get('/{id}/edit_ajax', [SupplierController:: class, 'edit_ajax' ]);       // menampilkan halaman form edit level
            Route:: get('/{id}/delete_ajax', [SupplierController:: class, 'confirm_ajax' ]); // confirm delete data user
            Route:: delete('/{id}/delete_ajax', [SupplierController:: class, 'delete_ajax' ]); // menghapus data user
            Route::get('/import', [SupplierController::class, 'import']);
            Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
            Route::get('/export_excel', [SupplierController::class, 'export_excel']);
            Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);
            Route::get('/template_supplier', [SupplierController::class, 'template_supplier']);
        });

        Route::group(['prefix' => 'stok'], function() {
            Route::get('/', [StokController::class, 'index']);
            Route::post('/list', [StokController::class, 'list'])->name('stok.list');
            Route::get('/create_ajax', [StokController::class, 'create'])->name('stok.create');
            Route::post('/', [StokController::class, 'store']);
            Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax'])->name('stok.show.ajax');
            Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']);
            Route::delete('/{id}', [StokController::class, 'destroy']);
            Route::get('/import', [StokController::class, 'import']);
            Route::post('/import_ajax', [StokController::class, 'import_ajax']);
            Route::get('/export_excel', [StokController::class, 'export_excel']);
            Route::get('/export_pdf', [StokController::class, 'export_pdf']);
        });

        Route::group(['prefix' => 'penjualan'], function() {
            Route::get('/', [PenjualanController::class, 'index']);
            Route::post('/list', [PenjualanController::class, 'list'])->name('penjualan.list');
            Route::get('/create_ajax', [PenjualanController::class, 'create_ajax'])->name('penjualan.create_ajax');
            Route::post('/penjualan/store_ajax', [PenjualanController::class, 'store_ajax'])->name('penjualan.store_ajax');
            Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax'])->name('penjualan.edit_ajax');
            Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax'])->name('penjualan.update_ajax');
            Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax'])->name('penjualan.show_ajax');
            Route::get('/{id}/confirm_ajax', [PenjualanController::class, 'confirm_ajax'])->name('penjualan.confirm_ajax');
            Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax'])->name('penjualan.delete_ajax');
            Route::post('/penjualan/import_ajax', [PenjualanController::class, 'import_ajax'])->name('penjualan.import_ajax');
            Route::get('/export_excel', [PenjualanController::class, 'export_excel']);
            Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']);
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




