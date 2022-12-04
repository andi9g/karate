<?php

namespace App\Http\Controllers;

use App\Models\superadminM;
use Hash;
use Illuminate\Http\Request;

class superadminC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = superadminM::get();

        return view('pages.pagesSuperadmin', [
            'superadmin' => $data,
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
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'namasuperadmin' => 'required',
        ]);
        
        
        try{
            $username = $request->username;
            $password = Hash::make($request->password);
            $namasuperadmin = $request->namasuperadmin;
        
            $store = new superadminM;
            $store->username = $username;
            $store->password = $password;
            $store->namasuperadmin = $namasuperadmin;
            $store->save();
            if($store) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\superadminM  $superadminM
     * @return \Illuminate\Http\Response
     */
    public function show(superadminM $superadminM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\superadminM  $superadminM
     * @return \Illuminate\Http\Response
     */
    public function edit(superadminM $superadminM)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\superadminM  $superadminM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, superadminM $superadminM, $idsuperadmin)
    {
        $request->validate([
            'namasuperadmin' => 'required',
            'password' => 'required',
        ]);
        
        
        try{
            $namasuperadmin = $request->namasuperadmin;
            $password = Hash::make($request->password);
        
            $update = superadminM::where('idsuperadmin', $idsuperadmin)->update([
                'namasuperadmin' => $namasuperadmin,
                'password' => $password,
            ]);
            if($update) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\superadminM  $superadminM
     * @return \Illuminate\Http\Response
     */
    public function destroy(superadminM $superadminM, $idsuperadmin)
    {
        try{
            $destroy = superadminM::where('idsuperadmin', $idsuperadmin)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
