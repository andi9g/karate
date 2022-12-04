<?php

namespace App\Http\Controllers;

use App\Models\tandingM;
use App\Models\penilaianM;
use App\Models\pesertatandingM;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class penilaianC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idadmin = $request->session()->get('idadmin');
        $idlapangan = $request->session()->get('idlapangan');
        $urutan = $request->session()->get('urutan');
        $idjuri = $request->session()->get('id');

        $data = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->join('kelas', 'kelas.idkelas', 'tanding.idkelas')
        ->join('bagian', 'bagian.idbagian', 'tanding.idbagian')
        ->join('lomba', 'lomba.idlomba', 'tanding.idlomba')
        ->join('regu', 'regu.idregu', 'tanding.idregu')
        ->join('lapangan', 'lapangan.idlapangan', 'tanding.idlapangan')
        ->where('pertandingan.sah', true)
        ->where('tanding.idadmin', $idadmin)
        ->where('tanding.idlapangan', $idlapangan)
        ->where('tanding.selesai', false)
        ->where('pesertatanding.selesai', false)
        ->orderBy('tanding.index', 'asc')
        ->orderBy('pesertatanding.urutan', 'asc')
        ->select('tanding.*', 'peserta.namapeserta', 'peserta.gambar', 'pesertatanding.idpesertatanding', 'pesertatanding.urutan', 'kelas.namakelas', 'bagian.namabagian', 'regu.namaregu','peserta.kontingen', 'lapangan.namalapangan')
        ->take(1)->get();
        

        return view('juri.pagesPenilaian', [
            'data' => $data,
            'idjuri' => $idjuri,
            'idlapangan' => $idlapangan,
        ]);

    }

    public function nilai(Request $request, $idpesertatanding, $idjuri, $idlapangan)
    {
        $request->validate([
            'tec' => 'required',
            'ath' => 'required',
        ]);
        
        
        try{
            $data = pesertatandingM::join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
            ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
            ->where('pesertatanding.idpesertatanding', $idpesertatanding)
            ->where('pesertatanding.idpesertatanding', $idpesertatanding)
            ->select('peserta.namapeserta', 'peserta.kontingen', 'pesertatanding.urutan')
            ->first();
            
            $tec = (float)$request->tec;
            $ath = (float)$request->ath;
        
            $store = new penilaianM;
            $store->idpesertatanding = $idpesertatanding;
            $store->idjuri = $idjuri;
            $store->idlapangan = $idlapangan;
            $store->nt = $tec;
            $store->na = $ath;
            $store->waktu = 99;
            $store->save();

            if($store) {
                return redirect()->back()->with('success', "<h5>
                    Success, <br>
                    Name : $data->namapeserta <br>
                    Kontingen : $data->kontingen <br>
                    Order : $data->urutan <br>
                    <br>
                    TEC Point : $tec <br>
                    ATH Point : $ath <br>
                </h5>");
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
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
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function show(tandingM $tandingM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function edit(tandingM $tandingM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tandingM $tandingM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tandingM  $tandingM
     * @return \Illuminate\Http\Response
     */
    public function destroy(tandingM $tandingM)
    {
        //
    }
}
