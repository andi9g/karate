<?php

namespace App\Http\Controllers;

use App\Models\pertandinganM;
use App\Models\lombaM;
use App\Models\bagianM;
use App\Models\tandingM;
use App\Models\reguM;
use App\Models\kelasM;
use App\Models\pesertatandingM;

use PDF;
use Illuminate\Http\Request;

class reguC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $lomba = lombaM::where('ket', true)
        ->select('idlomba', 'namalomba')->get();
        
        return view('pages.pagesTanding', [
            'lomba' => $lomba,
            'keyword' => $keyword,
        ]);
    }


    public function cetakSatuan(Request $request, $idlomba, $idkelas)
    {
        if($idlomba == "none" || $idkelas == "none") {
            $lomba = lombaM::get();
            $kelas = kelasM::get();
        }else {
            $lomba = lombaM::where('idlomba', $idlomba)->get();
            $kelas = kelasM::where('idkelas', $idkelas)->get();
        }

        $data = [];
        foreach ($lomba as $l) {
            $data2 = [];
            foreach ($kelas as $k) {
                $bagian = bagianM::get();
                
                $data3 = [];
                
                foreach ($bagian as $b) {
                    $regu = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
                    ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
                    ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
                    ->join('regu', 'regu.idregu', 'tanding.idregu')
                    ->join('lomba', 'lomba.idlomba', 'tanding.idlomba')
                    ->where('lomba.ket', true)
                    ->where('pertandingan.sah', true)
                    ->where('tanding.idlomba', $l->idlomba)
                    ->orderBy('tanding.index', 'asc')
                    ->orderBy('pesertatanding.urutan', 'asc')
                    ->where('tanding.idkelas', $k->idkelas)
                    ->where('tanding.idbagian', $b->idbagian)
                    ->where('regu.namaregu', 'like', "Pool %")
                    ->groupBy('tanding.idregu')
                    ->groupBy('regu.namaregu')
                    ->select('tanding.idregu','regu.namaregu')
                    ->get();

                    $data4 = [];
                    foreach ($regu as $r) {
                        $arr = [];
                        $tanding = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
                        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
                        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
                        ->join('regu', 'regu.idregu', 'tanding.idregu')
                        ->join('lomba', 'lomba.idlomba', 'tanding.idlomba')
                        ->where('lomba.ket', true)
                        ->where('pertandingan.sah', true)
                        ->where('tanding.idlomba', $l->idlomba)
                        ->orderBy('tanding.index', 'asc')
                        ->orderBy('pesertatanding.urutan', 'asc')
                        ->where('tanding.idkelas', $k->idkelas)
                        ->where('tanding.idbagian', $b->idbagian)
                        ->where('tanding.idregu', $r->idregu)
                        ->where('regu.namaregu', 'like', "Pool %")
                        ->select('peserta.namapeserta', 'tanding.*', 'peserta.kontingen','pesertatanding.urutan')
                        ->get();
                        
                        // dd($tanding);
                        $arr[] = $tanding;
                        $data4[] = [
                            'namaregu' => $r->namaregu,
                            'tanding' => $arr,
                        ];
                    }
                    $data3[] = [
                        'namabagian' => $b->namabagian,
                        'regu' => $data4,
                    ];
                }
                $data2[] = [
                    'namakelas' => $k->namakelas,
                    'bagian' => $data3,
                ];

            }
            $data[] = [
                'namalomba' => $l->namalomba,
                'kelas' => $data2,
            ];
        }

        // dd($data);
        $pdf = PDF::loadView('cetak.regu', [
            'data' => $data,
        ])->setPaper('a4');

        return $pdf->stream('Urutan_Peserta.pdf');

    }


    public function peserta(Request $request, $idlomba, $idkelas, $idbagian)
    {
        // try{
            $namalomba = lombaM::where('idlomba', $idlomba)->first()->namalomba;
        $namakelas = kelasM::where('idkelas', $idkelas)->first()->namakelas;
        $bagian = bagianM::where('idbagian', $idbagian)->first()->namabagian;

        $keyword = empty($request->keyword)?"":$request->keyword;
        $regu_get = empty($request->regu)?"":$request->regu;

        $kelas = kelasM::get();
        $regu = reguM::where('namaregu', 'like', "pool %")->get();
        $lomba = lombaM::where('ket', true)->get();

        $pesertatanding = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->join('regu', 'regu.idregu', 'tanding.idregu')
        ->join('lomba', 'lomba.idlomba', 'tanding.idlomba')
        ->where('lomba.ket', true)
        ->where('pertandingan.sah', true)
        ->where('tanding.idlomba', $idlomba)
        ->orderBy('tanding.index', 'asc')
        ->where('tanding.idkelas', $idkelas)
        ->where('tanding.idbagian', $idbagian)
        ->where(function ($query) use ($keyword) {
            $query->where('peserta.namapeserta', 'like', "%$keyword%");
        })
        ->where(function ($query) use ($regu_get){
            $query->where('tanding.idregu', 'like', "$regu_get%");
        })
        ->select('pesertatanding.*', 'peserta.namapeserta','peserta.kontingen','regu.namaregu', 'pesertatanding.urutan', 'tanding.idtanding','tanding.idregu')
        ->orderBy('regu.idregu', 'asc')
        ->orderBy('pesertatanding.urutan', 'asc')
        ->get();

        return view('pages.pagesPesertaRegu',[
            'namalomba' => $namalomba,
            'namakelas' => $namakelas,
            'bagian' => $bagian,

            'kelas' => $kelas,
            'lomba' => $lomba,
            'regu' => $regu,

            //data
            'pesertatanding' => $pesertatanding,

            //id
            'idlomba' => $idlomba,
            'idkelas' => $idkelas,
            'idbagian' => $idbagian,
            'regu_get' => $regu_get,
        ]);
        // }catch(\Throwable $th){
        //     return redirect('pendaftar')->with('toast_error', 'Belum ada peserta lomba/regu');
        // }
        

    }

    public function cari(Request $request, $idlomba, $idkelas, $idbagian)
    {
        $idlomba = empty($request->lomba)?$idlomba:$request->lomba;
        $idkelas = empty($request->kelas)?$idkelas:$request->kelas;
        $idbagian = empty($request->bagian)?$idbagian:$request->bagian;

        return redirect('regu/'.$idlomba.'/'.$idkelas.'/'.$idbagian.'/peserta');
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
     * @param  \App\Models\pertandinganM  $pertandinganM
     * @return \Illuminate\Http\Response
     */
    public function show(pertandinganM $pertandinganM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pertandinganM  $pertandinganM
     * @return \Illuminate\Http\Response
     */
    public function edit(pertandinganM $pertandinganM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pertandinganM  $pertandinganM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pertandinganM $pertandinganM, $idpesertatanding)
    {
        $request->validate([
            'urutan' => 'required',
        ]);
        
        
        try{
            // $regu = $request->regu;
            $urutan = empty($request->urutan)?"":$request->urutan;
            

            $update = pesertatandingM::where('idpesertatanding', $idpesertatanding)->update([
                'urutan' => $urutan,
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
     * @param  \App\Models\pertandinganM  $pertandinganM
     * @return \Illuminate\Http\Response
     */
    public function destroy(pertandinganM $pertandinganM, $idpesertatanding)
    {
        try{
            $destroy = pesertatandingM::where('idpesertatanding', $idpesertatanding)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
