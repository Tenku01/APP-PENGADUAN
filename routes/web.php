<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TanggapanController;

Route::resource('tanggapan', TanggapanController::class);

// Admin/Petugas
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function() {
        Route::get('/laporan', function () {
            return view('pages.admin.laporan');
        })->name('laporan');
        
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('pengaduans/{id}/detail', [PengaduanController::class, 'detail'])->name('pengaduans.detail');

        Route::resource('pengaduans', 'PengaduanController');

        Route::get('admin/pengaduan/{id}/edit', [TanggapanController::class, 'edit'])->name('tanggapan.edit');
        Route::put('admin/pengaduan/{id}', [TanggapanController::class, 'update'])->name('tanggapan.update');
        
        Route::resource('tanggapan', 'TanggapanController');

        Route::get('masyarakat', 'AdminController@masyarakat');
        Route::resource('petugas', 'PetugasController');

        Route::get('laporan', 'AdminController@laporan');
        Route::get('laporan/cetak', 'AdminController@cetak');
        Route::get('pengaduan/cetak/{id}', 'AdminController@pdf');
        Route::get('admin/pengaduan/{id}/detail', [PengaduanController::class, 'show'])->name('pengaduan.show');
Route::get('admin/tanggapan/{id}', [TanggapanController::class, 'show'])->name('tanggapan.show');

});


use App\Http\Controllers\UserController;

Route::get('check-email', [UserController::class, 'checkEmail'])->name('check-email');



// Masyarakat
Route::prefix('user')
    ->middleware(['auth', 'MasyarakatMiddleware'])
    ->group(function() {
		Route::get('/', 'MasyarakatController@index')->name('masyarakat-dashboard');
        Route::resource('pengaduan', 'MasyarakatController');
        Route::get('pengaduan', 'MasyarakatController@lihat');
});





require __DIR__.'/auth.php';
