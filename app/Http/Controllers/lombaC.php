<?php

namespace App\Http\Controllers;

use App\Models\lombaM;
use Illuminate\Http\Request;

class lombaC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $lomba = lombaM::orderBy('ket', 'desc')
        ->orderBy('created_at', 'desc')
        ->where(function ($query) use ($keyword) {
            $query->where('namalomba', 'like', "%".$keyword."%")
            ->orWhere('akses', 'like', $keyword."%");
        })->paginate(15);

        $lomba->appends($request->only(['keyword', 'limit']));

        return view('pages.pagesLomba', [
            'lomba' => $lomba,
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
        $akses = $request->akses;
        if($akses=="intern") {
            $request->validate([
                'namalomba' => 'required',
                'proposal' => 'required',
                'tanggalberkas' => 'required',
                'tanggallomba' => 'required',
                'tanggaltutup' => 'required',
                'wa1' => 'required',
                'wa2' => 'required',
                'tahun' => 'required',
                'akses' => 'required',
                'intern' => 'required',
            ]);
        }else {
            $request->validate([
                'namalomba' => 'required',
                'proposal' => 'required',
                'tanggalberkas' => 'required',
                'tanggallomba' => 'required',
                'tanggaltutup' => 'required',
                'wa1' => 'required',
                'wa2' => 'required',
                'tahun' => 'required',
                'akses' => 'required',
            ]);
        }
        
        
        try{
            $namalomba = $request->namalomba;
            $tanggalberkas = $request->tanggalberkas;
            $proposal = $request->proposal;
            $tanggallomba = $request->tanggallomba;
            $tanggaltutup = $request->tanggaltutup;
            $wa1 = $request->wa1;
            $wa2 = $request->wa2;
            $tahun = $request->tahun;
            $ket = true;
            if($akses == "intern"){
                $akses = $request->intern;
            }else {
                $akses = null;
            }

        
            $store = new lombaM;
            $store->namalomba = $namalomba;
            $store->proposal = $proposal;
            $store->tanggalberkas = $tanggalberkas;
            $store->tanggallomba = $tanggallomba;
            $store->tanggaltutup = $tanggaltutup;
            $store->wa1 = $wa1;
            $store->wa2 = $wa2;
            $store->tahun = $tahun;
            $store->ket = $ket;
            $store->akses = $akses;
            $store->save();
            if($store) {
                return redirect('lomba')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('lomba')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\lombaM  $lombaM
     * @return \Illuminate\Http\Response
     */
    public function show(lombaM $lombaM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\lombaM  $lombaM
     * @return \Illuminate\Http\Response
     */
    public function edit(lombaM $lombaM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\lombaM  $lombaM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lombaM $lombaM, $idlomba)
    {
        $akses = $request->akses;
        if($akses=="intern") {
            $request->validate([
                'namalomba' => 'required',
                'proposal' => 'required',
                'tanggalberkas' => 'required',
                'tanggallomba' => 'required',
                'tanggaltutup' => 'required',
                'wa1' => 'required',
                'wa2' => 'required',
                'tahun' => 'required',
                'akses' => 'required',
                'intern' => 'required',
            ]);
        }else {
            $request->validate([
                'namalomba' => 'required',
                'proposal' => 'required',
                'tanggalberkas' => 'required',
                'tanggallomba' => 'required',
                'tanggaltutup' => 'required',
                'wa1' => 'required',
                'wa2' => 'required',
                'tahun' => 'required',
            ]);
        }
        
        
        try{
            $namalomba = $request->namalomba;
            $tanggalberkas = $request->tanggalberkas;
            $proposal = $request->proposal;
            $tanggallomba = $request->tanggallomba;
            $tanggaltutup = $request->tanggaltutup;
            $wa1 = $request->wa1;
            $wa2 = $request->wa2;
            $tahun = $request->tahun;
            $ket = true;
            if($akses == "intern"){
                $akses = $request->intern;
            }else {
                $akses = null;
            }

        
            $update = lombaM::where('idlomba', $idlomba)->update([
                "namalomba" => $namalomba,
                "proposal" => $proposal,
                "tanggalberkas" => $tanggalberkas,
                "tanggallomba" => $tanggallomba,
                "tanggaltutup" => $tanggaltutup,
                "wa1" => $wa1,
                "wa2" => $wa2,
                "tahun" => $tahun,
                "ket" => $ket,
                "akses" => $akses,
            ]);

            if($update) {
                return redirect('lomba')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('lomba')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\lombaM  $lombaM
     * @return \Illuminate\Http\Response
     */
    public function destroy(lombaM $lombaM, $idlomba)
    {
        try{
            $destroy = lombaM::where('idlomba', $idlomba)->delete();
            if($destroy) {
                return redirect('lomba')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('lomba')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function proses(Request $request, $idlomba)
    {
        
        
        try{
            $cek = lombaM::where('idlomba', $idlomba)->first();
            $ket = $cek->ket;
            if ($ket == 1) {
                $ket = false;
            }else {
                $ket = 1;
            }
            
            $update = lombaM::where('idlomba', $idlomba)->update([
                'ket' => $ket,
            ]);
            if($update) {
                return redirect('lomba')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('lomba')->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
