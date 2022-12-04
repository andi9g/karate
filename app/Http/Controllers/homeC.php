<?php

namespace App\Http\Controllers;

use App\Models\adminM;
use App\Models\pertandinganM;
use App\Models\lombaM;
use App\Models\kelasM;
use Illuminate\Http\Request;

class homeC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $belumterferifikasi = pertandinganM::join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        ->where('pertandingan.sah', false)
        ->where('lomba.ket', true)
        ->count();
        $terferifikasi = pertandinganM::join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        ->where('pertandingan.sah', true)
        ->where('lomba.ket', true)
        ->count();

        $lomba = lombaM::where('ket', true)->get();

        $kelas = kelasM::get();

        return view('pages.pagesHome', [
            'belumterferifikasi' => $belumterferifikasi,
            'terferifikasi' => $terferifikasi,
            'lomba' => $lomba,
            'kelas' => $kelas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function show(adminM $adminM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function edit(adminM $adminM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, adminM $adminM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function destroy(adminM $adminM)
    {
        //
    }
}
