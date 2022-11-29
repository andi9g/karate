<?php

namespace App\Http\Controllers;

use App\Models\lapanganM;
use Illuminate\Http\Request;

class lapanganC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $lapangan = lapanganM::where('namalapangan', 'like', $keyword.'%')
        ->paginate(15);

        $lapangan->appends($request->only(['limit', 'keyword']));

        return view('pages.pagesLapangan', [
            'lapangan' => $lapangan,
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
            'namalapangan' => 'required',
        ]);
        
        
        try{
            $namalapangan = $request->namalapangan;
        
            $store = new lapanganM;
            $store->namalapangan = $namalapangan;
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
     * @param  \App\Models\lapanganM  $lapanganM
     * @return \Illuminate\Http\Response
     */
    public function show(lapanganM $lapanganM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\lapanganM  $lapanganM
     * @return \Illuminate\Http\Response
     */
    public function edit(lapanganM $lapanganM, $idlapangan)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\lapanganM  $lapanganM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lapanganM $lapanganM, $idlapangan)
    {
        $request->validate([
            'namalapangan' => 'required',
        ]);
        
        
        try{
            $namalapangan = $request->namalapangan;
        
            $update = lapanganM::where('idlapangan', $idlapangan)->update([
                'namalapangan' => $namalapangan,
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
     * @param  \App\Models\lapanganM  $lapanganM
     * @return \Illuminate\Http\Response
     */
    public function destroy(lapanganM $lapanganM, $idlapangan)
    {
        try{
            $destroy = lapanganM::where('idlapangan', $idlapangan)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
