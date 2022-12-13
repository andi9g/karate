<?php

namespace App\Http\Controllers;

use App\Models\adminM;
use Illuminate\Http\Request;
use App\Models\tandingM;
use App\Models\penilaianM;
use App\Models\pesertatandingM;
use App\Models\kelasM;
use App\Models\bagianM;
use App\Models\lombaM;
use Illuminate\Support\Collection;

class monitorC extends Controller
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
        // $idjuri = $request->session()->get('id');
        // dd($idadmin);

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
        ->select('tanding.*', 'peserta.namapeserta', 'peserta.gambar', 'pesertatanding.idpesertatanding', 'pesertatanding.urutan', 'kelas.namakelas', 'bagian.namabagian', 'regu.namaregu','peserta.kontingen', 'lapangan.namalapangan', 'pertandingan.idbagian', 'pesertatanding.namagroup')
        ->take(1)->get();

        $jumlahData = count($data);
        return view('monitor.tanding', [
            'data' => $data,
            'jumlahdata' => $jumlahData,
        ]);
    }



    public function hasil(Request $request)
    {
        try{
            $idadmin = $request->session()->get('idadmin');
            $idlapangan = $request->session()->get('idlapangan');
            $urutan = $request->session()->get('urutan');
    
            $ambil = tandingM::where('selesai', true)
            ->orderBy('updated_at', 'desc')
            ->where('ket2', 100)
            ->first();
    
            $namakelas = kelasM::where('idkelas', $ambil->idkelas)->first()->namakelas;
            $namalomba = lombaM::where('idlomba', $ambil->idlomba)->first()->namalomba;
            $namabagian = bagianM::where('idbagian', $ambil->idbagian)->first()->namabagian;
            $idtanding = $ambil->idtanding;
            // dd($namabagian." ".$namakelas." ".$namalomba. " ". $idtanding);
            $data = pesertatandingM::join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
            ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
            ->join('tanding', 'tanding.idtanding', 'pesertatanding.idtanding')
            ->where('pertandingan.sah', true)
            ->where('pesertatanding.idtanding', $idtanding)
            ->orderBy('pesertatanding.urutan', 'asc')
            ->select('pesertatanding.*', 'peserta.idpeserta', 'peserta.gambar', 'peserta.kontingen', 'peserta.namapeserta', 'tanding.ket2')
            ->get();
    
            return view('monitor.hasil', [
                'namakelas' => $namakelas,
                'namalomba' => $namalomba,
                'namabagian' => $namabagian,
                'data' => $data,
            ]);
        
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
    public function store(Request $request, $idpesertatanding, $waktu)
    {   
        
        try{
            if($waktu == true) {
                $waktu = $request->waktu;
                $ex = explode(":", $waktu);
                $menit = (int) $ex[0];
                $detik = (int) $ex[1];

                if($menit == 0 && $detik == 0) {
                    $waktu = 0;
                }else {
                    $waktu = (double)($menit.".".$detik);
                }
                
            }else {
                $waktu = 99;
            }

            $cek = penilaianM::where('idpesertatanding', $idpesertatanding)->count();

            if($cek > 0) {
                $update1 = penilaianM::where('idpesertatanding', $idpesertatanding)->update([
                    'waktu' => $waktu,
                ]);
            }

            $update2 = pesertatandingM::where('idpesertatanding', $idpesertatanding)->update([
                'selesai' => true,
            ]);

            if($update1 || $update2) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
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
