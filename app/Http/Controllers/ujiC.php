<?php

namespace App\Http\Controllers;

use App\Models\adminM;
use Illuminate\Http\Request;

class ujiC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function session_start(Request $request)
    {
        try{
            $admin = adminM::first();
            $idadmin = $admin->idadmin;
            $namaadmin = $admin->username;

            $request->session()->put('login', true);
            $request->session()->put('id', $idadmin);
            $request->session()->put('posisi', 'admin');
            $request->session()->put('namaadmin', $namaadmin);

            return redirect('tanding')->with('success', 'Session Start');
        
        }catch(\Throwable $th){
            return redirect('tanding')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function session_stop(Request $request)
    {
        try{
            $request->session()->flush();

            return redirect('tanding')->with('success', 'Session Stop');
        
        }catch(\Throwable $th){
            return redirect('tanding')->with('toast_error', 'Terjadi kesalahan');
        }
    }


    public function index()
    {
        //
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
