<?php

use Illuminate\Support\Facades\Route;




//auth
Route::get('login', 'authC@index');
Route::post('login', 'authC@proses')->name('proses.login');
Route::get('logout', 'authC@logout');

Route::put('ubahpassword', 'authC@ubahpassword')->name('ubah.password');


Route::middleware(['GerbangLogin'])->group(function () {
    Route::get('home', 'homeC@index');

    Route::middleware(['GerbangJuri'])->group(function () {
        //juri
        Route::get('nilai', 'penilaianC@index');
        Route::post('nilai/{idpesertatanding}/{idjuri}/{idlapangan}', 'penilaianC@nilai')->name('nilai.menilai');
    });
    
    Route::middleware(['GerbangSuperadmin'])->group(function () {
        //superadmin -------------------------------------------------------------------------------
        // lomba
        Route::resource('lomba', 'lombaC'); 
        Route::post('proses/lomba/{idlomba}' , 'lombaC@proses')->name('lomba.proses');
        //pendaftar
        Route::get('pendaftar/{idkelas}/list', 'pendaftarC@pendaftar');
        Route::put('pendaftar/{idpertandingan}/cancel', 'pendaftarC@cancel')->name('pendaftar.cancel');
        Route::get('pendaftar', 'pendaftarC@index');
        Route::put('pendaftar/{idpertandingan}/proses', 'pendaftarC@store')->name('pendaftar.update');
        Route::post('pendaftar/{idpertandingan}/kelola/regu', 'pendaftarC@kelolaregu')->name('kelola.regu');
        
        //regu
        Route::resource('regu', 'reguC');
        Route::get('regu/{idlomba}/{idkelas}/{idbagian}/peserta', 'reguC@peserta')->name('peserta.regu');
        Route::post('regu/{idlomba}/{idkelas}/{idbagian}/cari', 'reguC@cari')->name('cari.regu');
        Route::get('cetak_regu/satuan/{idlomba}/{idkelas}', 'reguC@cetakSatuan')->name('cetak.regu.satuan');
        //lapangan
        Route::resource('lapangan', 'lapanganC');
        
        //adminS
        Route::resource('admin', 'adminC');
        Route::put('admin/{idadmin}/reset/password', 'adminC@reset')->name('reset.admin');
        Route::get('cetak_admin/{idadmin}', 'adminC@cetak')->name('admin.cetak');
        
        Route::resource('superadmin', 'superadminC');
        Route::resource('pengaturan', 'pengaturanC');
        
        Route::get('cetak_pendaftar/{idkelas}/filter/cetak', 'pendaftarC@cetakfilter')->name('cetak.filter');
        
        //endsuperadmin ---------------------------------------------------------------------
        
    });
    
    
    Route::middleware(['GerbangAdmin'])->group(function () {
        //admin ----------------------------------------------------------------------------
        //data lomba -> melakukan pemilihan kelas pertandingan yang dilakukan admin, jika telah di pilih maka admin lain tidak dapat memilih pertandingan tersebut
        Route::get('tanding', 'tandingC@index');
        Route::get('tanding/{idlomba}/{idbagian}/{idkelas}/bagian', 'tandingC@bagian')->name('pilih.bagian');
        Route::get('tanding/{idlomba}/{idbagian}/{idkelas}/{idregu}/{idtanding}', 'tandingC@regu')->name('pilih.regu');
        Route::put('tanding/{idlomba}/{idbagian}/{idkelas}/tanding', 'tandingC@pilih')->name('pilih.tanding');
        Route::post('tanding/{idlomba}/{idbagian}/{idkelas}/kelompok/tanding', 'tandingC@kelompok')->name('kelompok.tanding');
        Route::post('finish/{idlomba}/{idbagian}/{idkelas}/tanding', 'tandingC@finish')->name('finish.tanding');
        Route::put('tanding/{idtanding}/urutan', 'tandingC@urutan')->name('ubah.urutan');
        Route::delete('tanding/{idtanding}/hapus', 'tandingC@destroy')->name('hapus.tanding.utama');
        Route::get('monitor', 'monitorC@index');
        Route::get('hasil', 'monitorC@hasil');
        Route::post('monitor/{idpesertatanding}/{waktu}', 'monitorC@store')->name('monitor.selesai');
        
        
        
        Route::get('cetak/tanding/{idlomba}/{idbagian}/{idkelas}', 'tandingC@cetak')->name('tanding.cetak');
    });
    
    
    
    
    
    
});
