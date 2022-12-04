<?php

namespace App\Http\Controllers;

use App\Models\pertandinganM;
use App\Models\pesertatandingM;
use App\Models\tandingM;
use App\Models\kelasM;
use App\Models\lombaM;
use App\Models\bagianM;
use Illuminate\Http\Request;

use PDF;

class pendaftarC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function pendaftar(Request $request, $idkelas)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;
        $bagian = empty($request->bagian)?"":$request->bagian;
        $lomba = empty($request->lomba)?"":$request->lomba;
        $sah = empty($request->sah)?"":(($request->sah == 'none')?"0":$request->sah);
        // dd($sah);
        $namakelas = kelasM::where('idkelas', $idkelas)->first()->namakelas;

        $datalomba = lombaM::select('idlomba', 'namalomba')->where('ket', true)->get();

        $pertandingan = pertandinganM::join('kelas', 'kelas.idkelas', 'pertandingan.idkelas')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        ->join('bagian', 'bagian.idbagian', 'pertandingan.idbagian')
        ->where('lomba.ket', true)
        ->where('pertandingan.idkelas', $idkelas)
        ->where(function ($query) use ($bagian) {
            $query->where('bagian.namabagian', 'like', "$bagian%");
        })->where(function ($query) use ($sah) {
            $query->where('pertandingan.sah', 'like', "$sah%");
        })
        ->where(function ($query) use ($lomba) {
            $query->where('lomba.idlomba', 'like', "$lomba%");
        })
        ->where(function ($query) use ($keyword) {
            $query->where('peserta.namapeserta', 'like', "%$keyword%")
            ->orWhere('peserta.kontingen', 'like', "$keyword%");
        })->select('peserta.*','pertandingan.*', 'bagian.namabagian')
        ->paginate(20);

        $pertandingan->appends($request->only(['keyword', 'limit']));

        return view('pages.pagesPendaftar', [
            'pertandingan' => $pertandingan,
            'bagian' => $bagian,
            'lomba' => $lomba,
            'datalomba' => $datalomba,
            'namakelas' => $namakelas,
            'sah' => $sah,
            'idkelas' => $idkelas,
        ]);

    }

    public function index()
    {
        $pertandingan = kelasM::join('pertandingan','pertandingan.idkelas', 'kelas.idkelas')
        ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        ->where('lomba.ket', true)
        ->groupBy('kelas.namakelas')
        ->groupBy('kelas.idkelas')
        ->select('kelas.namakelas', 'kelas.idkelas')
        ->get();

        return view('pages.pagesMenuPendaftar', [
            'pertandingan' => $pertandingan,
        ]);
    }


    public function cetakfilter(Request $request, $idkelas)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;
        $bagian = empty($request->bagian)?"":$request->bagian;
        $lomba = empty($request->lomba)?"":$request->lomba;
        $sah = empty($request->sah)?"":(($request->sah == 'none')?"0":$request->sah);
        // dd($sah);
        $namakelas = pertandinganM::join('kelas', 'kelas.idkelas', 'pertandingan.idkelas')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        ->join('bagian', 'bagian.idbagian', 'pertandingan.idbagian')
        ->where('lomba.ket', true)
        ->where('pertandingan.idkelas', 'like', $idkelas."%")
        ->where(function ($query) use ($bagian) {
            $query->where('bagian.namabagian', 'like', "$bagian%");
        })->where(function ($query) use ($sah) {
            $query->where('pertandingan.sah', 'like', "$sah%");
        })
        ->where(function ($query) use ($lomba) {
            $query->where('lomba.idlomba', 'like', "$lomba%");
        })
        ->where(function ($query) use ($keyword) {
            $query->where('peserta.namapeserta', 'like', "%$keyword%")
            ->orWhere('peserta.kontingen', 'like', "$keyword%");
        })->groupBy('pertandingan.idkelas')
        ->groupBy('kelas.namakelas')
        ->groupBy('pertandingan.sah')
        ->groupBy('pertandingan.idbagian')
        ->groupBy('bagian.namabagian')
        ->groupBy('pertandingan.idlomba')
        ->groupBy('lomba.namalomba')
        ->select('pertandingan.idkelas', 'pertandingan.idbagian', 'pertandingan.idlomba', 'kelas.namakelas', 'bagian.namabagian', 'lomba.namalomba', 'pertandingan.sah')
        ->get();

        // $datalomba = lombaM::select('idlomba', 'namalomba')->where('ket', true)->get();

        // $pertandingan = pertandinganM::join('kelas', 'kelas.idkelas', 'pertandingan.idkelas')
        // ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        // ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        // ->join('bagian', 'bagian.idbagian', 'pertandingan.idbagian')
        // ->where('lomba.ket', true)
        // ->where('pertandingan.idkelas', $idkelas)
        // ->where(function ($query) use ($bagian) {
        //     $query->where('bagian.namabagian', 'like', "$bagian%");
        // })->where(function ($query) use ($sah) {
        //     $query->where('pertandingan.sah', 'like', "$sah%");
        // })
        // ->where(function ($query) use ($lomba) {
        //     $query->where('lomba.idlomba', 'like', "$lomba%");
        // })
        // ->where(function ($query) use ($keyword) {
        //     $query->where('peserta.namapeserta', 'like', "%$keyword%")
        //     ->orWhere('peserta.kontingen', 'like', "$keyword%");
        // })->select('peserta.*','pertandingan.*', 'bagian.namabagian')
        // ->get();

        

        $pdf = PDF::loadView('laporan.pagesPendaftarFilter',[
            // 'pertandingan' => $pertandingan,
            'bagian' => $bagian,
            'lomba' => $lomba,
            // 'datalomba' => $datalomba,
            'namakelas' => $namakelas,
            'sah' => $sah,
            'idkelas' => $idkelas,
        ])->setPaper('a4');

        return $pdf->stream('peserta_tanding.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $idpertandingan)
    {
        try{
            $update = pertandinganM::where('idpertandingan', $idpertandingan)->update([
                'sah' => true,
            ]);
            if($update) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function cancel(Request $request, $idpertandingan)
    {
        try{
            $update = pertandinganM::where('idpertandingan', $idpertandingan)->update([
                'sah' => false,
            ]);
            if($update) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }


    public function kelolaregu(Request $request, $idpertandingan)
    {
        $request->validate([
            'regu'=>'required'
        ]);

        try{
            
            $datapertandingan = pertandinganM::
            where('idpertandingan', $idpertandingan)
            ->first();
            $idkelas = $datapertandingan->idkelas;
            $idlomba = $datapertandingan->idlomba;
            $idbagian = $datapertandingan->idbagian;
            $idregu = $request->regu;

            $tanding = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
            ->where('pesertatanding.idpertandingan', $idpertandingan)
            ->where('tanding.idkelas', $idkelas)
            ->where('tanding.idlomba', $idlomba)
            ->where('tanding.idbagian', $idbagian)
            ->select('tanding.idregu','pesertatanding.idtanding');

            $idtandinglama = "";
            if ($tanding->count() > 0) {
                $tanding = $tanding->first();
                $idregulama = $tanding->idregu;    
                $idtandinglama = $tanding->idtanding;    
            }

            $tanding = tandingM::where('idregu', $idregu)
            ->where('idbagian', $idbagian)
            ->where('idlomba', $idlomba)
            ->where('idkelas', $idkelas)
            ->count();

            $index = tandingM::where('idbagian', $idbagian)
            ->where('idlomba', $idlomba)
            ->where('idkelas', $idkelas)
            ->count() + 1;

            if ($tanding == 0) {
                $tambah = new tandingM;
                $tambah->idkelas = $idkelas;
                $tambah->idbagian = $idbagian;
                $tambah->idregu = $idregu;
                $tambah->idlomba = $idlomba;
                $tambah->index = $index;
                $tambah->waktu = false;
                $tambah->ket = 'primary';
                $tambah->save();
            }

            $tanding = tandingM::where('idkelas', $idkelas)
            ->where('idregu', $idregu)
            ->where('idlomba', $idlomba)
            ->where('idbagian', $idbagian)->first();
            $idtanding = $tanding->idtanding;

            $hapus = pesertatandingM::where('idpertandingan', $idpertandingan)->where('idtanding', $idtandinglama)->delete();

            $tambah = new pesertatandingM;
            $tambah->idtanding = $idtanding;
            $tambah->idpertandingan = $idpertandingan;
            $tambah->save();


            if (!empty($idtandinglama)) {
                # code...
                $cek = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
                ->where('tanding.idregu', $idregulama)
                ->where('tanding.idkelas', $idkelas)
                ->where('tanding.idbagian', $idbagian)
                ->count();
    
                if($cek === 0) {
                    tandingM::where('idregu', $idregulama)
                    ->where('idkelas', $idkelas)
                    ->where('idbagian', $idbagian)->delete();
                }
            }



            if($tambah) {
                return redirect()->back()->with('toast_success', 'Success');
            }
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
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
    public function update(Request $request, pertandinganM $pertandinganM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pertandinganM  $pertandinganM
     * @return \Illuminate\Http\Response
     */
    public function destroy(pertandinganM $pertandinganM)
    {
        //
    }
}
