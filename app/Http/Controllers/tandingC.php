<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Models\pertandinganM;
use App\Models\lombaM;
use App\Models\bagianM;
use App\Models\penilaianM;
use App\Models\tandingM;
use App\Models\pengaturanM;
use App\Models\reguM;
use App\Models\adminM;
use App\Models\kelasM;
use App\Models\pesertatandingM;
use Illuminate\Http\Request;
use Session;
use PDF;
use ArrayObject;
class tandingC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $lomba = lombaM::join('tanding', 'tanding.idlomba', 'lomba.idlomba')
        ->join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
        ->join('kelas', 'kelas.idkelas', 'tanding.idkelas')
        ->join('bagian', 'bagian.idbagian', 'tanding.idbagian')
        ->groupBy('lomba.idlomba')
        ->groupBy('lomba.namalomba')
        ->where('lomba.ket', true)
        ->select('lomba.idlomba', 'namalomba')
        ->get();

        $idadmin = $request->session()->get('id');
        $lapangan = adminM::where('idadmin', $idadmin)->first();
        $idlapangan = $lapangan->idlapangan;

        $namaadmin = $request->session()->get('namaadmin');
        
        return view('admin.pagesTanding', [
            'lomba' => $lomba,
            'keyword' => $keyword,
            'idadmin' => $idadmin,
            'namaadmin' => $namaadmin,
            'idlapangan' => $idlapangan,
        ]);
    }

    public function bagian(Request $request, $idlomba, $idbagian, $idkelas)
    {
        $namalomba = lombaM::where('idlomba', $idlomba)->first()->namalomba;
        $bagian = bagianM::where('idbagian', $idbagian)->first()->namabagian;
        $kelas = kelasM::where('idkelas', $idkelas)->first()->namakelas;
        
        $jumlahjuri = pengaturanM::first()->jumlahjuri;

        $jumlah = tandingM::where('idlomba', $idlomba)
        ->where('idbagian', $idbagian)
        ->where('idkelas', $idkelas)
        ->count();

        $regu = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
        ->where('tanding.idlomba', $idlomba)
        ->where('tanding.idbagian', $idbagian)
        ->where('tanding.idkelas', $idkelas)
        ->orderBy('tanding.index', 'asc')
        ->select('tanding.*', 'regu.namaregu')
        ->get();

        $finish = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
        ->where('tanding.idlomba', $idlomba)
        ->where('tanding.idbagian', $idbagian)
        ->where('tanding.idkelas', $idkelas)
        ->where('tanding.ket2', 100)
        ->count();
        
        return view('admin.pagesRegu', [
            'regu' => $regu,
            'namalomba' => $namalomba,
            'bagian' => $bagian,
            'kelas' => $kelas,
            'jumlah' => $jumlah,
            'jumlahjuri' => $jumlahjuri,
            'finish' => $finish,
            //id
            'idlomba' => $idlomba,
            'idkelas' => $idkelas,
            'idbagian' => $idbagian,  
        ]);
    }

    public function cetak(Request $request, $idlomba, $idbagian, $idkelas)
    {
        $idadmin = $request->session()->get('id');
        $idlapangan = adminM::where('idadmin', $idadmin)->first()->idlapangan;

        $jumlahjuri = pengaturanM::first()->jumlahjuri;

        $namalomba = lombaM::where('idlomba', $idlomba)->first()->namalomba;
        $namabagian = bagianM::where('idbagian', $idbagian)->first()->namabagian;
        $namakelas = kelasM::where('idkelas', $idkelas)->first()->namakelas;


        // dd($ket2);

        $cek = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
        ->where('tanding.idlomba', $idlomba)
        ->where('tanding.idbagian', $idbagian)
        ->where('tanding.idkelas', $idkelas)
        ->where('tanding.ket2', '=', 13)
        ->where('selesai', true)
        ->orderBy('index', 'asc')
        ->select('tanding.*', 'regu.namaregu')
        ->count();
        
        $jumlahCek = $cek;

        if($cek == 2 || $cek == 0) {
            $tanding = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
            ->where('tanding.idlomba', $idlomba)
            ->where('tanding.idbagian', $idbagian)
            ->where('tanding.idkelas', $idkelas)
            ->where('tanding.selesai', true)
            ->orderBy('tanding.index', 'asc')
            ->where('regu.ket', '!=', 100)
            ->select('tanding.*', 'regu.namaregu')
            ->get();
        }else {
            $tanding = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
            ->where('tanding.idlomba', $idlomba)
            ->where('tanding.idbagian', $idbagian)
            ->where('tanding.idkelas', $idkelas)
            ->where('tanding.selesai', true)
            ->orderBy('tanding.index', 'asc')
            ->where('regu.ket', '!=', 100)
            ->where('regu.ket', '!=', 13)
            ->select('tanding.*', 'regu.namaregu')
            ->get();
        }

        $datafinal = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->where('tanding.idlomba', $idlomba)
        ->where('tanding.idbagian', $idbagian)
        ->where('tanding.idkelas', $idkelas)
        ->where('tanding.idlapangan', $idlapangan)
        ->where('pertandingan.sah', true)
        ->where('tanding.ket2', 100)
        ->where('tanding.selesai', true)
        ->orderBy('pesertatanding.urutan', 'asc')
        ->select('tanding.*', 'peserta.namapeserta','pesertatanding.urutan','pesertatanding.idpesertatanding', 'peserta.kontingen', 'pesertatanding.namagroup')
        ->get();

        foreach ($tanding as $t) {
            $data = [];
            $peserta = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
            ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
            ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
            ->where('tanding.idlomba', $idlomba)
            ->where('tanding.idbagian', $idbagian)
            ->where('tanding.idkelas', $idkelas)
            ->where('tanding.idlapangan', $idlapangan)
            ->where('tanding.idtanding', $t->idtanding)
            ->where('pertandingan.sah', true)
            ->where('tanding.selesai', true)
            ->orderBy('pesertatanding.urutan', 'asc')
            ->select('tanding.*', 'peserta.namapeserta','pesertatanding.urutan','pesertatanding.idpesertatanding', 'pesertatanding.namagroup')
            ->get();
    
            foreach ($peserta as $item) {
                $nilaiNA = [];
                $nilaiNT = [];
                for ($i=1; $i <= $jumlahjuri; $i++) { 
                    $nilai = penilaianM::
                    join('juri', 'juri.idjuri', 'penilaian.idjuri')
                    ->join('pesertatanding', 'pesertatanding.idpesertatanding', 'penilaian.idpesertatanding')
                    ->join('tanding','tanding.idtanding', 'pesertatanding.idtanding')
                    ->where('tanding.idlomba', $item->idlomba)
                    ->where('tanding.idbagian', $item->idbagian)
                    ->where('tanding.idkelas', $item->idkelas)
                    ->where('tanding.idregu', $item->idregu)
                    ->where('tanding.idtanding', $item->idtanding)
                    ->where('pesertatanding.idpesertatanding', $item->idpesertatanding)
                    ->where('juri.posisi', $i)
                    ->where('penilaian.waktu', '!=', 0.0)
                    ->orderBy('juri.posisi', 'asc')
                    ->select('penilaian.*', 'juri.posisi')
                    ->first();
    
                    $nilaiNT[] = [
                        'nilai' => empty($nilai->nt)?0:$nilai->nt,
                        'juri' => $i,
                        'ket' => true,
                    ]; 
                    
                    $nilaiNA[] = [
                        'nilai' => empty($nilai->na)?0:$nilai->na,
                        'juri' => $i,
                        'ket' => true,
                    ];  
    
    
                }
                $ntmax = $nilaiNT;
                $ntmin = $nilaiNT;
                rsort($ntmax);
                sort($ntmin);
                if($jumlahjuri == 7) {
                    //TCH max
                    $arr = array_search($ntmax[0], $nilaiNT);
                    $nilaiNT[$arr]['ket'] = false;
                    $arr = array_search($ntmax[1], $nilaiNT);
                    $nilaiNT[$arr]['ket'] = false;
                    
                    //TCH min
                    $arr = array_search($ntmin[0], $nilaiNT);
                    $nilaiNT[$arr]['ket'] = false;
                    $arr = array_search($ntmin[1], $nilaiNT);
                    $nilaiNT[$arr]['ket'] = false;
                }else if($jumlahjuri == 5) {
                    //TCH max
                    $arr = array_search($ntmax[0], $nilaiNT);
                    $nilaiNT[$arr]['ket'] = false;
    
                    //TCH min
                    $arr = array_search($ntmin[0], $nilaiNT);
                    $nilaiNT[$arr]['ket'] = false;
                }
    
                
                $namax = $nilaiNA;
                $namin = $nilaiNA;
                rsort($namax);
                sort($namin);
                if($jumlahjuri == 7) {
                    //ATH max
                    $arr = array_search($namax[0], $nilaiNA);
                    $nilaiNA[$arr]['ket'] = false;
                    $arr = array_search($namax[1], $nilaiNA);
                    $nilaiNA[$arr]['ket'] = false;
                    //ATH min
                    $arr = array_search($namin[0], $nilaiNA);
                    $nilaiNA[$arr]['ket'] = false;
                    $arr = array_search($namin[1], $nilaiNA);
                    $nilaiNA[$arr]['ket'] = false;
    
                }elseif ($jumlahjuri == 5) {
                    $arr = array_search($namax[0], $nilaiNA);
                    $nilaiNA[$arr]['ket'] = false;
    
                    $arr = array_search($namin[0], $nilaiNA);
                    $nilaiNA[$arr]['ket'] = false;
                }
                
    
                $tec = 0;
                $tec_dis = 0;
                foreach ($nilaiNT as $nt) {
                    if ($nt['ket'] != false) {
                        $tec = $tec + $nt['nilai'];
                    }else {
                        $tec_dis = $tec_dis + $nt['nilai'];
                    }
                }
                $ath = 0;
                $ath_dis = 0;
                foreach ($nilaiNA as $na) {
                    if ($na['ket'] != false) {
                        $ath = $ath + $na['nilai'];
                    }else{
                        $ath_dis = $ath_dis + $na['nilai'];
                    }
                }
                
                $tec_rumus = $tec * 0.7;
                $tec_dis = $tec_dis * 0.7;
                $ath_rumus = $ath * 0.3;
                $ath_dis = $ath_dis * 0.3;
                $total = $tec_rumus + $ath_rumus;
                $total_dis = $tec_dis + $ath_dis;
    
    
                
                $cek1 = tandingM::where('idtanding', $item->idtanding)->where('ket2', 13)->count();
                $cek = tandingM::where('ket2', 13)->count();
                // dd($cek);
                $view = false;
                if($cek1 == 1 && $cek == 1) {
                    $view = true;
                }
    
                
                if($item->ket2 == 100) {
                    $view = true;
                }
                
                
    
                $data[] = new Collection([
                    'urutan' => $item->urutan,
                    'idtanding' => $item->idtanding,
                    'idpesertatanding' => $item->idpesertatanding,
                    'namagroup' => $item->namagroup,
                    'waktu' => $item->waktu,
                    'view' => $view,
                    'namapeserta' => $item->namapeserta,
                    'tec' => $nilaiNT,
                    'tec_total' => $tec,
                    'tec_total_rumus' => $tec_rumus,
                    'ath' => $nilaiNA,
                    'ath_total' => $ath,
                    'ath_total_rumus' => $ath_rumus,
                    'total' => $total,
                ]);
    
                // $nt = array(
                //     $item->idpesertatanding => $nt_,
                //     $item->idpesertatanding => $na_,
                // );
    
                // $nilai_tec[$item->idpesertatanding] = array($nilai);
            }

            $kket = ($t->ket=="primary")?"":"(".$t->ket.")";
            $cetak[] = new Collection([
                'index' => $t->index,
                'regu' => $t->namaregu." ".$kket,
                'data' => $data,
            ]);


        }

        // dd($data);
        // dd($data[0]);//ATH max
            // $arr = array_search($namax[0], $nilaiNA);
            // $nilaiNA[$arr]['ket'] = false;
            // $arr = array_search($namax[1], $nilaiNA);
            // $nilaiNA[$arr]['ket'] = false;
            // //ATH min
            // $arr = array_search($namin[0], $nilaiNA);
            // $nilaiNA[$arr]['ket'] = false;
            // $arr = array_search($namin[1], $nilaiNA);
            // $nilaiNA[$arr]['ket'] = false;


        $pdf = PDF::loadView('cetak.hasil', [
            'cetak' => $cetak,
            'jumlahjuri' => $jumlahjuri,
            'jumlahcek' => $jumlahCek,

            'namalomba' => $namalomba,
            'namabagian' => $namabagian,
            'namakelas' => $namakelas,

            'datafinal' => $datafinal,
        ])->setPaper('a4');

        return $pdf->stream('Ranking.pdf');
    }




    public function regu(Request $request, $idlomba, $idbagian, $idkelas, $idregu, $idtanding)
    {
        $idadmin = $request->session()->get('id');
        $idlapangan = adminM::where('idadmin', $idadmin)->first()->idlapangan;

        $jumlahjuri = pengaturanM::first()->jumlahjuri;

        $ambil = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
        ->where('tanding.idtanding', $idtanding)
        ->select('regu.namaregu', 'tanding.*')->first();

        $namaregu = $ambil->namaregu." ".$ambil->ket2;
        $namalomba = lombaM::where('idlomba', $idlomba)->first()->namalomba;
        $namabagian = bagianM::where('idbagian', $idbagian)->first()->namabagian;
        $namakelas = kelasM::where('idkelas', $idkelas)->first()->namakelas;
        
        
        // dd($ket2);
        $peserta = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->where('tanding.idlomba', $idlomba)
        ->where('tanding.idbagian', $idbagian)
        ->where('tanding.idkelas', $idkelas)
        ->where('tanding.idregu', $idregu)
        ->where('tanding.idlapangan', $idlapangan)
        ->where('tanding.idtanding', $idtanding)
        ->where('pertandingan.sah', true)
        ->orderBy('pesertatanding.urutan', 'asc')
        ->select('tanding.*', 'peserta.namapeserta','pesertatanding.urutan','pesertatanding.idpesertatanding', 'pesertatanding.namagroup')
        ->get();

        
        foreach ($peserta as $item) {
            $nilaiNA = [];
            $nilaiNT = [];
            for ($i=1; $i <= $jumlahjuri; $i++) { 
                $nilai = penilaianM::
                join('juri', 'juri.idjuri', 'penilaian.idjuri')
                ->join('pesertatanding', 'pesertatanding.idpesertatanding', 'penilaian.idpesertatanding')
                ->join('tanding','tanding.idtanding', 'pesertatanding.idtanding')
                ->where('tanding.idlomba', $idlomba)
                ->where('tanding.idbagian', $idbagian)
                ->where('tanding.idkelas', $idkelas)
                ->where('tanding.idregu', $idregu)
                ->where('tanding.idtanding', $item->idtanding)
                ->where('pesertatanding.idpesertatanding', $item->idpesertatanding)
                ->where('juri.posisi', $i)
                ->where('penilaian.waktu', '!=', 0.0)
                ->orderBy('juri.posisi', 'asc')
                ->select('penilaian.*', 'juri.posisi')
                ->first();

                $nilaiNT[] = [
                    'nilai' => empty($nilai->nt)?0:$nilai->nt,
                    'juri' => $i,
                    'ket' => true,
                ]; 
                
                $nilaiNA[] = [
                    'nilai' => empty($nilai->na)?0:$nilai->na,
                    'juri' => $i,
                    'ket' => true,
                ];  


            }
            $ntmax = $nilaiNT;
            $ntmin = $nilaiNT;
            rsort($ntmax);
            sort($ntmin);
            if($jumlahjuri == 7) {
                //TCH max
                $arr = array_search($ntmax[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
                $arr = array_search($ntmax[1], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
                
                //TCH min
                $arr = array_search($ntmin[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
                $arr = array_search($ntmin[1], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
            }else if($jumlahjuri == 5) {
                //TCH max
                $arr = array_search($ntmax[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;

                //TCH min
                $arr = array_search($ntmin[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
            }

            
            $namax = $nilaiNA;
            $namin = $nilaiNA;
            rsort($namax);
            sort($namin);
            if($jumlahjuri == 7) {
                //ATH max
                $arr = array_search($namax[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
                $arr = array_search($namax[1], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
                //ATH min
                $arr = array_search($namin[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
                $arr = array_search($namin[1], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;

            }elseif ($jumlahjuri == 5) {
                $arr = array_search($namax[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;

                $arr = array_search($namin[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
            }
            

            $tec = 0;
            $tec_dis = 0;
            foreach ($nilaiNT as $nt) {
                if ($nt['ket'] != false) {
                    $tec = $tec + $nt['nilai'];
                }else {
                    $tec_dis = $tec_dis + $nt['nilai'];
                }
            }
            $ath = 0;
            $ath_dis = 0;
            foreach ($nilaiNA as $na) {
                if ($na['ket'] != false) {
                    $ath = $ath + $na['nilai'];
                }else{
                    $ath_dis = $ath_dis + $na['nilai'];
                }
            }
            
            $tec_rumus = $tec * 0.7;
            $tec_dis = $tec_dis * 0.7;
            $ath_rumus = $ath * 0.3;
            $ath_dis = $ath_dis * 0.3;
            $total = $tec_rumus + $ath_rumus;
            $total_dis = $tec_dis + $ath_dis;


            
            $cek1 = tandingM::where('idtanding', $item->idtanding)->where('ket2', 13)->count();
            $cek = tandingM::where('ket2', 13)->count();
            // dd($cek);
            $view = false;
            if($cek1 == 1 && $cek == 1) {
                $view = true;
            }

            
            if($item->ket2 == 100) {
                $view = true;
            }
            


            $data[] = new Collection([
                'urutan' => $item->urutan,
                'idtanding' => $item->idtanding,
                'idpesertatanding' => $item->idpesertatanding,
                'view' => $view,
                'namapeserta' => $item->namapeserta,
                'tec' => $nilaiNT,
                'tec_total' => $tec,
                'tec_total_rumus' => $tec_rumus,
                'ath' => $nilaiNA,
                'ath_total' => $ath,
                'ath_total_rumus' => $ath_rumus,
                'total' => $total,
                'waktu' => $item->waktu,
                'namagroup' => $item->namagroup,
            ]);

            // $nt = array(
            //     $item->idpesertatanding => $nt_,
            //     $item->idpesertatanding => $na_,
            // );

            // $nilai_tec[$item->idpesertatanding] = array($nilai);
        }
        // dd($data);
        // dd($data[0]);//ATH max
            $arr = array_search($namax[0], $nilaiNA);
            $nilaiNA[$arr]['ket'] = false;
            $arr = array_search($namax[1], $nilaiNA);
            $nilaiNA[$arr]['ket'] = false;
            //ATH min
            $arr = array_search($namin[0], $nilaiNA);
            $nilaiNA[$arr]['ket'] = false;
            $arr = array_search($namin[1], $nilaiNA);
            $nilaiNA[$arr]['ket'] = false;
        

        

        return view('admin.pagesDataRegu', [
            'peserta' => $peserta,
            'jumlahjuri' => $jumlahjuri,
            'data' => $data,

            'namaregu' => $namaregu,
            'namalomba' => $namalomba,
            'namabagian' => $namabagian,
            'namakelas' => $namakelas,

            //id
            'idlomba' => $idlomba,
            'idkelas' => $idkelas,
            'idbagian' => $idbagian,
            'idregu' => $idregu,
            'idadmin' => $idadmin,
        ]);
    }

    public function urutan(Request $request, $idtanding)
    {
        $request->validate([
            'index' => 'required',
        ]);
        
        
        try{
            $index = $request->index;
        
            $update = tandingM::where('idtanding', $idtanding)->update([
                'index' => $index,
            ]);
            if($update) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }


    public function pilih(Request $request, $idlomba, $idbagian, $idkelas)
    {
        $request->validate([
            'idadmin' => 'required',
            'waktulomba' => 'required',
        ]);
        
        
        try{
            $idadmin = $request->idadmin;
            $waktulomba = $request->waktulomba;

            $admin = adminM::where('idadmin', $idadmin)->first();
            $idlapangan = $admin->idlapangan;

            if($waktulomba=='none'){
                $waktulomba=false;
            }else {
                $waktulomba=true;
            }
        
            $update = tandingM::where('idlomba', $idlomba)
            ->where('idbagian', $idbagian)
            ->where('idkelas', $idkelas)
            ->update([
                'idadmin' => $idadmin,
                'waktu' => $waktulomba,
                'idlapangan' => $idlapangan,
            ]);

            if($update) {
                return redirect()->back()->with('toast_success', 'success');
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

    public function urut($idlomba, $idbagian, $idkelas, $idregu, $idtanding)
    {
        $idadmin = Session::get('id');
        // $idlapangan = adminM::where('idadmin', $idadmin)->first()->idlapangan;

        $jumlahjuri = pengaturanM::first()->jumlahjuri;

        $peserta = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
        ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
        ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
        ->join('regu', 'regu.idregu', 'tanding.idregu')
        ->where('tanding.idlomba', $idlomba)
        ->where('tanding.idbagian', $idbagian)
        ->where('tanding.idkelas', $idkelas)
        ->where('tanding.idregu', $idregu)
        ->where('tanding.idtanding', $idtanding)
        ->where('pertandingan.sah', true)
        ->orderBy('pesertatanding.urutan', 'asc')
        ->select('tanding.*', 'peserta.namapeserta','pesertatanding.urutan','pesertatanding.idpesertatanding', 'pertandingan.idpertandingan', 'regu.namaregu', 'pesertatanding.namagroup')
        ->get();

        // dd($peserta);
        
        foreach ($peserta as $item) {
            $nilaiNA = [];
            $nilaiNT = [];

            for ($i=1; $i <= $jumlahjuri; $i++) { 
                $nilai = penilaianM::
                join('juri', 'juri.idjuri', 'penilaian.idjuri')
                ->join('pesertatanding', 'pesertatanding.idpesertatanding', 'penilaian.idpesertatanding')
                ->join('tanding','tanding.idtanding', 'pesertatanding.idtanding')
                ->where('tanding.idlomba', $idlomba)
                ->where('tanding.idbagian', $idbagian)
                ->where('tanding.idkelas', $idkelas)
                ->where('tanding.idregu', $idregu)
                ->where('penilaian.waktu', '!=', 0.0)
                ->where('pesertatanding.idpesertatanding', $item->idpesertatanding)
                ->where('juri.posisi', $i)
                ->orderBy('juri.posisi', 'asc')
                ->select('penilaian.*', 'juri.posisi')
                ->first();

                $nilaiNT[] = [
                    'nilai' => empty($nilai->nt)?0:$nilai->nt,
                    'juri' => $i,
                    'ket' => true,
                ]; 
                
                $nilaiNA[] = [
                    'nilai' => empty($nilai->na)?0:$nilai->na,
                    'juri' => $i,
                    'ket' => true,
                ];  


            }
            $ntmax = $nilaiNT;
            $ntmin = $nilaiNT;
            rsort($ntmax);
            sort($ntmin);
            if($jumlahjuri == 7) {
                //TCH max
                $arr = array_search($ntmax[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
                $arr = array_search($ntmax[1], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
                
                //TCH min
                $arr = array_search($ntmin[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
                $arr = array_search($ntmin[1], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
            }else if($jumlahjuri == 5) {
                //TCH max
                $arr = array_search($ntmax[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;

                //TCH min
                $arr = array_search($ntmin[0], $nilaiNT);
                $nilaiNT[$arr]['ket'] = false;
            }

            
            $namax = $nilaiNA;
            $namin = $nilaiNA;
            rsort($namax);
            sort($namin);
            if($jumlahjuri == 7) {
                //ATH max
                $arr = array_search($namax[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
                $arr = array_search($namax[1], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
                //ATH min
                $arr = array_search($namin[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
                $arr = array_search($namin[1], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;

            }elseif ($jumlahjuri == 5) {
                $arr = array_search($namax[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;

                $arr = array_search($namin[0], $nilaiNA);
                $nilaiNA[$arr]['ket'] = false;
            }

            $tec = 0;
            $tec_dis = 0;
            foreach ($nilaiNT as $nt) {
                if ($nt['ket'] != false) {
                    $tec = $tec + $nt['nilai'];
                }else {
                    $tec_dis = $tec_dis + $nt['nilai'];
                }
            }
            $ath = 0;
            $ath_dis = 0;
            foreach ($nilaiNA as $na) {
                if ($na['ket'] != false) {
                    $ath = $ath + $na['nilai'];
                }else{
                    $ath_dis = $ath_dis + $na['nilai'];
                }
            }
            
            $tec_rumus = $tec * 0.7;
            $tec_dis = $tec_dis * 0.7;
            $ath_rumus = $ath * 0.3;
            $ath_dis = $ath_dis * 0.3;
            $total = $tec_rumus + $ath_rumus;
            $total_dis = $tec_dis + $ath_dis;
            // $total = rand(7.0, 10);

            $data[] = new Collection([
                'total' => $total,
                'dis' => $total_dis,
                'urutan' => $item->urutan,
                'idlomba' => $item->idlomba,
                'idregu' => $item->idregu,
                'idkelas' => $item->idkelas,
                'idbagian' => $item->idbagian,
                'namaregu' => $item->namaregu,
                'waktu' => $item->waktu,
                'idpertandingan' => $item->idpertandingan,
                'idpesertatanding' => $item->idpesertatanding,
                'namapeserta' => $item->namapeserta,
                'tec' => $nilaiNT,
                'tec_total' => $tec,
                'tec_total_rumus' => $tec_rumus,
                'ath' => $nilaiNA,
                'ath_total' => $ath,
                'ath_total_rumus' => $ath_rumus,
                'namagroup' => $item->namagroup,
            ]);

        }
        return $data;
    }

    

    public function finish(Request $request, $idlomba, $idbagian, $idkelas)
    {

        $idadmin = $request->session()->get('id');
        $lapangan = adminM::where('idadmin', $idadmin)->first();
        $idlapangan = $lapangan->idlapangan;

        $final1_2 = tandingM::where('idlomba', $idlomba)
        ->where('idbagian', $idbagian)
        ->where('idkelas', $idkelas)
        ->where('ket2', 1);

        $cek = tandingM::where('idlomba', $idlomba)
        ->where('idbagian', $idbagian)
        ->where('idkelas', $idkelas)
        ->where('selesai', true)
        ->count();
        if($cek > 0) {
            return redirect()->back()->with('warning', 'Pertandingan telah diselesaikan')->withInput();
        }

        $idregu = reguM::where('ket', 100)->first()->idregu;
        
        if($final1_2->count() == 1) {

            $idtanding_final1_2 = $final1_2->first()->idtanding;
            $idregu_final1_2 = $final1_2->first()->idregu;
            
            $final3 = tandingM::where('idlomba', $idlomba) 
            ->where('idbagian', $idbagian)
            ->where('idkelas', $idkelas)
            ->where('ket2', 13);

            if($final3->count() == 1) {
                $data1 = $this->urut($idlomba, $idbagian, $idkelas,$idregu_final1_2, $idtanding_final1_2);
                
                rsort($data1);

                $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                $i1 = 1;
                foreach ($data1 as $d1) {
                        
                    if($i1 <=2) {
                        
                    $cek = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->count();
                    
                    if($cek == 0) {
                        $tambah = new tandingM;
                        $tambah->idadmin = $idadmin;
                        $tambah->idlapangan = $idlapangan;
                        $tambah->waktu = $d1['waktu'];
                        $tambah->idkelas = $d1['idkelas'];
                        $tambah->idbagian = $d1['idbagian'];
                        $tambah->idregu = $idregu;
                        $tambah->idlomba = $d1['idlomba'];
                        $tambah->index = $j;
                        $tambah->ket = null;
                        $tambah->ket2 = 100;
                        $tambah->save();
                    }else {
                        return redirect()->back()->with('toast_error', 'Telah melakukan finish')->withInput();
                    }

                    $id = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->first()->idtanding;

                    $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                    
                    $tambah = new pesertatandingM;
                    $tambah->idtanding = $id;
                    $tambah->idpertandingan = $d1['idpertandingan'];
                    $tambah->namagroup = $d1['namagroup'];
                    $tambah->urutan = $pt;
                    $tambah->save();
                    }

                    $i1++;
                    


                }

                $final3 = tandingM::where('idlomba', $idlomba) 
                ->where('idbagian', $idbagian)
                ->where('idkelas', $idkelas)
                ->where('ket2', 13)
                ->get();

                $h = 1;
                foreach ($final3 as $f3) {
                    $idtanding_final3 = $f3->idtanding;
                    $idregu_final3 = $f3->idregu;

                    
                    ${"datajuaratiga$h"} = $this->urut($idlomba, $idbagian, $idkelas,$idregu_final3, $idtanding_final3);
                    $h++;
                }

                rsort($datajuaratiga1);

                
                $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                $i1 = 1;
                foreach ($datajuaratiga1 as $d1) {
                        
                    if($i1 <=2) {
                        
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', 100)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = null;
                            $tambah->ket2 = 100;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', 100)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                    }

                    $i1++;
                    


                }

                $update = tandingM::where('idlomba', $idlomba)
                ->where('idbagian', $idbagian)
                ->where('idkelas', $idkelas)
                ->update([
                    'selesai' => true,
                ]);

                if($update) {
                    return redirect()->back()->with('success', 'Finishing Success')->withInput();
                }

            }elseif($final3->count() == 2) {
                $data1 = $this->urut($idlomba, $idbagian, $idkelas,$idregu_final1_2, $idtanding_final1_2);
                
                rsort($data1);

                $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                $i1 = 1;
                foreach ($data1 as $d1) {
                        
                    if($i1 <=2) {
                        
                    $cek = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->count();
                    
                    if($cek == 0) {
                        $tambah = new tandingM;
                        $tambah->idadmin = $idadmin;
                        $tambah->idlapangan = $idlapangan;
                        $tambah->waktu = $d1['waktu'];
                        $tambah->idkelas = $d1['idkelas'];
                        $tambah->idbagian = $d1['idbagian'];
                        $tambah->idregu = $idregu;
                        $tambah->idlomba = $d1['idlomba'];
                        $tambah->index = $j;
                        $tambah->ket = null;
                        $tambah->ket2 = 100;
                        $tambah->save();
                    }

                    $id = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->first()->idtanding;

                    $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                    
                    $tambah = new pesertatandingM;
                    $tambah->idtanding = $id;
                    $tambah->idpertandingan = $d1['idpertandingan'];
                    $tambah->namagroup = $d1['namagroup'];
                    $tambah->urutan = $pt;
                    $tambah->save();
                    }

                    $i1++;
                    


                }

                $final3 = tandingM::where('idlomba', $idlomba) 
                ->where('idbagian', $idbagian)
                ->where('idkelas', $idkelas)
                ->where('ket2', 13)
                ->get();

                $h = 1;
                foreach ($final3 as $f3) {
                    $idtanding_final3 = $f3->idtanding;
                    $idregu_final3 = $f3->idregu;

                    
                    ${"datajuaratiga$h"} = $this->urut($idlomba, $idbagian, $idkelas,$idregu_final3, $idtanding_final3);
                    $h++;
                }

                rsort($datajuaratiga1);
                rsort($datajuaratiga2);

                
                $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                $i1 = 1;
                foreach ($datajuaratiga1 as $d1) {
                        
                    if($i1 <=1) {
                        
                    $cek = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->count();
                    
                    if($cek == 0) {
                        $tambah = new tandingM;
                        $tambah->idadmin = $idadmin;
                        $tambah->idlapangan = $idlapangan;
                        $tambah->waktu = $d1['waktu'];
                        $tambah->idkelas = $d1['idkelas'];
                        $tambah->idbagian = $d1['idbagian'];
                        $tambah->idregu = $idregu;
                        $tambah->idlomba = $d1['idlomba'];
                        $tambah->index = $j;
                        $tambah->ket = null;
                        $tambah->ket2 = 100;
                        $tambah->save();
                    }

                    $id = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->first()->idtanding;

                    $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                    
                    $tambah = new pesertatandingM;
                    $tambah->idtanding = $id;
                    $tambah->idpertandingan = $d1['idpertandingan'];
                    $tambah->namagroup = $d1['namagroup'];
                    $tambah->urutan = $pt;
                    $tambah->save();
                    }

                    $i1++;
                    


                }
                $i1 = 1;
                foreach ($datajuaratiga2 as $d1) {
                        
                    if($i1 <=1) {
                        
                    $cek = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->count();
                    
                    if($cek == 0) {
                        $tambah = new tandingM;
                        $tambah->idadmin = $idadmin;
                        $tambah->idlapangan = $idlapangan;
                        $tambah->waktu = $d1['waktu'];
                        $tambah->idkelas = $d1['idkelas'];
                        $tambah->idbagian = $d1['idbagian'];
                        $tambah->idregu = $idregu;
                        $tambah->idlomba = $d1['idlomba'];
                        $tambah->index = $j;
                        $tambah->ket = null;
                        $tambah->ket2 = 100;
                        $tambah->save();
                    }

                    $id = tandingM::where('idregu', $idregu)
                    ->where('idkelas', $d1['idkelas'])
                    ->where('idlomba', $d1['idlomba'])
                    ->where('idbagian', $d1['idbagian'])
                    ->where('ket2', 100)
                    ->first()->idtanding;

                    $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                    
                    $tambah = new pesertatandingM;
                    $tambah->idtanding = $id;
                    $tambah->idpertandingan = $d1['idpertandingan'];
                    $tambah->namagroup = $d1['namagroup'];
                    $tambah->urutan = $pt;
                    $tambah->save();
                    }

                    $i1++;
                    


                }

                $update = tandingM::where('idlomba', $idlomba)
                ->where('idbagian', $idbagian)
                ->where('idkelas', $idkelas)
                ->update([
                    'selesai' => true,
                ]);

                if($update) {
                    return redirect()->back()->with('success', 'Finishing Success')->withInput();
                }
            }

        }

        if($final1_2->count() == 0) {
            $final = tandingM::where('idlomba', $idlomba)
            ->where('idbagian', $idbagian)
            ->where('idkelas', $idkelas)
            ->orderBy('index', 'desc')->first();

            $idtanding2 = $final->idtanding;
            $idregu2 = $final->idregu;

            $data1 = $this->urut($idlomba, $idbagian, $idkelas,$idregu2, $idtanding2);
                
            rsort($data1);

            // dd($data1);
            $j = tandingM::where('idkelas', $idkelas)
                ->where('idlomba', $idlomba)
                ->where('idbagian', $idbagian)
                // ->where('ket2', $ket2)
                ->count();
                $j = $j +1;

            $i1 = 1;
            foreach ($data1 as $d1) {
                    
                if($i1 <=4) {
                    
                $cek = tandingM::where('idregu', $idregu)
                ->where('idkelas', $d1['idkelas'])
                ->where('idlomba', $d1['idlomba'])
                ->where('idbagian', $d1['idbagian'])
                ->where('ket2', 100)
                ->count();
                
                if($cek == 0) {
                    $tambah = new tandingM;
                    $tambah->idadmin = $idadmin;
                    $tambah->idlapangan = $idlapangan;
                    $tambah->waktu = $d1['waktu'];
                    $tambah->idkelas = $d1['idkelas'];
                    $tambah->idbagian = $d1['idbagian'];
                    $tambah->idregu = $idregu;
                    $tambah->idlomba = $d1['idlomba'];
                    $tambah->index = $j;
                    $tambah->ket = null;
                    $tambah->ket2 = 100;
                    $tambah->save();
                }

                $id = tandingM::where('idregu', $idregu)
                ->where('idkelas', $d1['idkelas'])
                ->where('idlomba', $d1['idlomba'])
                ->where('idbagian', $d1['idbagian'])
                ->where('ket2', 100)
                ->first()->idtanding;

                $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                
                $tambah = new pesertatandingM;
                $tambah->idtanding = $id;
                $tambah->idpertandingan = $d1['idpertandingan'];
                $tambah->namagroup = $d1['namagroup'];
                $tambah->urutan = $pt;
                $tambah->save();
                }

                $i1++;

            }
            $update = tandingM::where('idlomba', $idlomba)
                ->where('idbagian', $idbagian)
                ->where('idkelas', $idkelas)
                ->update([
                    'selesai' => true,
                ]);

                if($update) {
                    return redirect()->back()->with('success', 'Finishing Success')->withInput();
                }
        }

    }

    public function kelompok(Request $request, $idlomba, $idbagian, $idkelas)
    {
        $request->validate([
            'regu'=>'required',
            'idtanding'=>'required'
        ]);


        try{
            $idadmin = $request->session()->get('id');
            $lapangan = adminM::where('idadmin', $idadmin)->first();
            $idlapangan = $lapangan->idlapangan;

            $idregu = $request->regu;
            $idtanding = $request->idtanding;
            // dd($idtanding);
            $cek = tandingM::where('idlomba', $idlomba)
            ->where('idbagian', $idbagian)
            ->where('idkelas', $idkelas)
            ->where('selesai', true)
            ->count();
            if($cek > 0) {
                return redirect()->back()->with('warning', 'Pertandingan telah diselesaikan')->withInput();
            }

            // dd($idtanding[0]);
            if(count($idtanding) == 2) {
                $cek = reguM::where('idregu', $idregu)->first();
                $ket3 = $cek->ket;
                //kode 8 untuk babak penyisihan
                if(((int)$cek->ket) == 8) {
                    

                    $ket1 = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
                    ->where('idtanding', $idtanding[0])->select('regu.ket')->first()->ket;
                    $ket2 = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
                    ->where('idtanding', $idtanding[1])->select('regu.ket')->first()->ket;
                    // dd($ket2);
                    if($ket1 != $ket2) {
                        return redirect()->back()->with('warning', 'Terjadi kesalahan');
                    }
                    // dd($ket2);

                    $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $idkelas)
                        ->where('idlomba', $idlomba)
                        ->where('idbagian', $idbagian)
                        ->where('ket2', $ket2)
                        ->count();
                    if($cek == 1) {
                        return redirect()->back()->with('warning', 'Terjadi kesalahan');
                    }
                    
                    $i =1;
                    foreach ($idtanding as $id) {
                        $ambil = tandingM::where('idtanding', $id)->first()->idregu;
                        
                        ${"data$i"} = $this->urut($idlomba, $idbagian, $idkelas,$ambil, $id);
                        $i++;
                    }
                    //20 itu pool A
                    //30 itu pool A
                    rsort($data1);
                    rsort($data2);

                    // dd($data1);

                    if ($ket2 == "20") {
                        $ket = "(1,2,3,4) A1 dan A2 => [Pool A]";
                    }else if($ket2 == "30") {
                        $ket = "(1,2,3,4) B1 dan B2 => [Pool B]";
                    }

                    $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                    $i1 = 1;
                    foreach ($data1 as $d1) {
                        
                        if($i1 <=4) {
                            
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = $ket;
                            $tambah->ket2 = $ket2;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                        }

                        $i1++;
                        


                    }

                    $i1 = 1;
                    foreach ($data2 as $d1) {
                        
                        if($i1 <=4) {
                            
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = $ket;
                            $tambah->ket2 = $ket2;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                        }

                        $i1++;
                        


                    }
                    
                
                    return redirect()->back()->with('success', 'Success');
                }

                if(((int)$cek->ket) == 1) {
                   

                    $cek2 = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $idkelas)
                        ->where('idlomba', $idlomba)
                        ->where('idbagian', $idbagian)
                        ->where('ket2', $cek->ket)
                        ->count();
                    if($cek2 == 1) {
                        return redirect()->back()->with('warning', 'Terjadi kesalahan');
                    }
                    
                    $i =1;
                    foreach ($idtanding as $id) {
                        $ambil = tandingM::where('idtanding', $id)->first()->idregu;
                        
                        ${"data$i"} = $this->urut($idlomba, $idbagian, $idkelas,$ambil, $id);
                        $i++;
                    }
                    //20 itu pool A
                    //30 itu pool A
                    rsort($data1);
                    rsort($data2);

                    // dd($data1);
                    $ket = null;

                    $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    ->count();
                    $j = $j +1;

                    $i1 = 1;
                    foreach ($data1 as $d1) {
                        
                        if($i1 <=1) {
                            
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket3)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = $ket;
                            $tambah->ket2 = $ket3;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket3)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                        }

                        $i1++;
                        


                    }

                    $i1 = 1;
                    foreach ($data2 as $d1) {
                        
                        if($i1 <= 1) {
                            
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket3)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = $ket;
                            $tambah->ket2 = $ket3;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket3)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                        }

                        $i1++;
                        


                    }
                    
                
                    return redirect()->back()->with('success', 'Success');
                }

                if(((int)$cek->ket) == 13) {
                   

                    $cek2 = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $idkelas)
                        ->where('idlomba', $idlomba)
                        ->where('idbagian', $idbagian)
                        ->where('ket2', $ket3)
                        ->count();
                    if($cek2 == 2) {
                        return redirect()->back()->with('warning', 'Terjadi kesalahan');
                    }
                    
                    $i =1;
                    foreach ($idtanding as $id) {
                        $ambil = tandingM::where('idtanding', $id)->first()->idregu;
                        $ambil1 = tandingM::where('idtanding', $id)->first()->ket;
                        $cari = explode('[', $ambil1);
                        
                        if(count($cari) == 2) {
                            $cari = str_replace(']','',$cari[1]);
                            $cari_ket[] = $cari;
                        }else {
                            $tt = tandingM::join('regu', 'tanding.idregu', 'regu.idregu')
                            ->where('tanding.idtanding', $id)->select("regu.namaregu")->first();
                        }
                        // dd($tt->namaregu);
                        $cari_ket[] = $tt->namaregu;

                        
                        ${"data$i"} = $this->urut($idlomba, $idbagian, $idkelas,$ambil, $id);
                        $i++;
                    }
                    // dd($cari_ket);
                    //20 itu pool A
                    //30 itu pool A
                    rsort($data1);
                    rsort($data2);
                    

                    $jml = count($data1) + count($data2);

                    $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    ->count();
                    $j = $j +1;

                    for ($i=0; $i < 2; $i++) { 
                        if($jml == 5 && $i == 0){
                            $ket = null;
                            $cek = tandingM::where('idregu', $idregu)
                            ->where('idkelas', $data1[0]['idkelas'])
                            ->where('idlomba', $data1[0]['idlomba'])
                            ->where('idbagian', $data1[0]['idbagian'])
                            ->where('ket2', $ket3)
                            ->count();
                            
                            if($cek == 0) {
                                $tambah = new tandingM;
                                $tambah->idadmin = $idadmin;
                                $tambah->idlapangan = $idlapangan;
                                $tambah->waktu = $data1[0]['waktu'];
                                $tambah->idkelas = $data1[0]['idkelas'];
                                $tambah->idbagian = $data1[0]['idbagian'];
                                $tambah->idregu = $idregu;
                                $tambah->idlomba = $data1[0]['idlomba'];
                                $tambah->index = $j + $i;
                                $tambah->ket = $ket;
                                $tambah->ket2 = $ket3;
                                $tambah->save();
                            }

                            $id = tandingM::where('idregu', $idregu)
                            ->where('idkelas', $data1[0]['idkelas'])
                            ->where('idlomba', $data1[0]['idlomba'])
                            ->where('idbagian', $data1[0]['idbagian'])
                            ->where('ket2', $ket3)
                            ->orderBy('idtanding', 'desc')
                            ->first()->idtanding;

                            $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                            
                            $tambah = new pesertatandingM;
                            $tambah->idtanding = $id;
                            $tambah->idpertandingan = $data1[1]['idpertandingan'];
                            $tambah->namagroup = $data1[1]['namagroup'];
                            $tambah->urutan = $pt;
                            $tambah->save();
                            $tambah = new pesertatandingM;
                            $tambah->idtanding = $id;
                            $tambah->idpertandingan = $data2[1]['idpertandingan'];
                            $tambah->namagroup = $data2[1]['namagroup'];
                            $tambah->urutan = $pt;
                            $tambah->save();
                            
                        }else if($jml > 5) {
                            if ($cari_ket[0] == "Pool A" && $cari_ket[1] == "Pool B") {
                                $ket = ["2A - 3B", "3A - 2B"];
                            }else if($cari_ket[0] == "Pool B" && $cari_ket[1] == "Pool A") {
                                $ket = ["2B - 3A", "3B - 2A"];
                            }
                            
                            $cek = tandingM::where('idregu', $idregu)
                            ->where('idkelas', $data1[0]['idkelas'])
                            ->where('idlomba', $data1[0]['idlomba'])
                            ->where('idbagian', $data1[0]['idbagian'])
                            ->where('ket2', $ket3)
                            ->count();
                            
                            if($cek < 3) {
                                $tambah = new tandingM;
                                $tambah->idadmin = $idadmin;
                                $tambah->idlapangan = $idlapangan;
                                $tambah->waktu = $data1[0]['waktu'];
                                $tambah->idkelas = $data1[0]['idkelas'];
                                $tambah->idbagian = $data1[0]['idbagian'];
                                $tambah->idregu = $idregu;
                                $tambah->idlomba = $data1[0]['idlomba'];
                                $tambah->index = $j + $i;
                                $tambah->ket = $ket[$i];
                                $tambah->ket2 = $ket3;
                                $tambah->save();
                            }

                            $id = tandingM::where('idregu', $idregu)
                            ->where('idkelas', $data1[0]['idkelas'])
                            ->where('idlomba', $data1[0]['idlomba'])
                            ->where('idbagian', $data1[0]['idbagian'])
                            ->where('ket2', $ket3)
                            ->orderBy('idtanding', 'desc')
                            ->first()->idtanding;

                            $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                            
                            if ($i==0) {
                                # code...
                                $tambah = new pesertatandingM;
                                $tambah->idtanding = $id;
                                $tambah->idpertandingan = $data1[1]['idpertandingan'];
                                $tambah->namagroup = $data1[1]['namagroup'];
                                $tambah->urutan = $pt;
                                $tambah->save();
                                $tambah = new pesertatandingM;
                                $tambah->idtanding = $id;
                                $tambah->idpertandingan = $data2[2]['idpertandingan'];
                                $tambah->namagroup = $data2[2]['namagroup'];
                                $tambah->urutan = $pt+1;
                                $tambah->save();
                            }else if($i==1) {
                                $tambah = new pesertatandingM;
                                $tambah->idtanding = $id;
                                $tambah->idpertandingan = $data1[2]['idpertandingan'];
                                $tambah->namagroup = $data1[2]['namagroup'];
                                $tambah->urutan = $pt;
                                $tambah->save();
                                $tambah = new pesertatandingM;
                                $tambah->idtanding = $id;
                                $tambah->idpertandingan = $data2[1]['idpertandingan'];
                                $tambah->namagroup = $data2[1]['namagroup'];
                                $tambah->urutan = $pt+1;
                                $tambah->save();
                            }
                            
                        }
                        // else {
                        //     return redirect()->back()->with('warning', 'Error')->withInput();
                        // }
                    }
                    
                
                    return redirect()->back()->with('success', 'Success');
                }

            }elseif(count($idtanding) == 1) {
                $cek = reguM::where('idregu', $idregu)->first();
                //kode 8 untuk babak penyisihan
                if(((int)$cek->ket) == 4) {
                    
                    $ket2 = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
                    ->where('idtanding', $idtanding[0])->select('tanding.ket2')->first()->ket2;

                    if (empty($ket2)) {
                        $ket2 = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
                        ->where('idtanding', $idtanding[0])->select('regu.ket')->first()->ket;
                    }
                    
                    $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $idkelas)
                        ->where('idlomba', $idlomba)
                        ->where('idbagian', $idbagian)
                        ->where('ket2', $ket2)
                        ->count();
                    if($cek == 1) {
                        return redirect()->back()->with('warning', 'Terjadi kesalahan');
                    }
                    
                    $i =1;
                    foreach ($idtanding as $id) {
                        $ambil = tandingM::where('idtanding', $id)->first()->idregu;
                        
                        ${"data$i"} = $this->urut($idlomba, $idbagian, $idkelas,$ambil, $id);
                        $i++;
                    }
                    //20 itu pool A
                    //30 itu pool A
                    rsort($data1);

                    // dd($data1);

                    if ($ket2 == "20" || $ket2 == "11") {
                        $ket = "(1,2,3 dan 4) => [Pool A]";
                    }else if($ket2 == "30" || $ket2 == "12") {
                        $ket = "(1,2,3 dan 4) => [Pool B]";
                    }

                    // dd($ket);
                    $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                    $i1 = 1;
                    foreach ($data1 as $d1) {
                        
                        if($i1 <=4) {
                            
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = $ket;
                            $tambah->ket2 = $ket2;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                        }

                        $i1++;
                        


                    }
                
                    return redirect()->back()->with('success', 'Success');
                }


                if(((int)$cek->ket) == 3) {
                    
                    $ket2 = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
                    ->where('idtanding', $idtanding[0])->select('tanding.ket2')->first()->ket2;

                    if (empty($ket2)) {
                        $ket2 = tandingM::join('regu', 'regu.idregu', 'tanding.idregu')
                        ->where('idtanding', $idtanding[0])->select('regu.ket')->first()->ket;
                    }
                    
                    $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $idkelas)
                        ->where('idlomba', $idlomba)
                        ->where('idbagian', $idbagian)
                        ->where('ket2', $ket2)
                        ->count();
                    if($cek == 1) {
                        return redirect()->back()->with('warning', 'Terjadi kesalahan');
                    }
                    
                    $i =1;
                    foreach ($idtanding as $id) {
                        $ambil = tandingM::where('idtanding', $id)->first()->idregu;
                        
                        ${"data$i"} = $this->urut($idlomba, $idbagian, $idkelas,$ambil, $id);
                        $i++;
                    }
                    //20 itu pool A
                    //30 itu pool A
                    rsort($data1);

                    // dd($data1);

                    if ($ket2 == "20" || $ket2 == "11") {
                        $ket = "(1,2 dan 3) => [Pool A]";
                    }else if($ket2 == "30" || $ket2 == "12") {
                        $ket = "(1,2 dan 3) => [Pool B]";
                    }

                    // dd($ket);
                    $j = tandingM::where('idkelas', $idkelas)
                    ->where('idlomba', $idlomba)
                    ->where('idbagian', $idbagian)
                    // ->where('ket2', $ket2)
                    ->count();
                    $j = $j +1;

                    $i1 = 1;
                    foreach ($data1 as $d1) {
                        
                        if($i1 <=3) {
                            
                        $cek = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->count();
                        
                        if($cek == 0) {
                            $tambah = new tandingM;
                            $tambah->idadmin = $idadmin;
                            $tambah->idlapangan = $idlapangan;
                            $tambah->waktu = $d1['waktu'];
                            $tambah->idkelas = $d1['idkelas'];
                            $tambah->idbagian = $d1['idbagian'];
                            $tambah->idregu = $idregu;
                            $tambah->idlomba = $d1['idlomba'];
                            $tambah->index = $j;
                            $tambah->ket = $ket;
                            $tambah->ket2 = $ket2;
                            $tambah->save();
                        }

                        $id = tandingM::where('idregu', $idregu)
                        ->where('idkelas', $d1['idkelas'])
                        ->where('idlomba', $d1['idlomba'])
                        ->where('idbagian', $d1['idbagian'])
                        ->where('ket2', $ket2)
                        ->first()->idtanding;

                        $pt = pesertatandingM::where('idtanding', $id)->count() + 1;
                        
                        $tambah = new pesertatandingM;
                        $tambah->idtanding = $id;
                        $tambah->idpertandingan = $d1['idpertandingan'];
                        $tambah->namagroup = $d1['namagroup'];
                        $tambah->urutan = $pt;
                        $tambah->save();
                        }

                        $i1++;
                        


                    }
                
                    return redirect()->back()->with('success', 'Success');
                }

            }


                return redirect()->back()->with('toast_error', 'Terjadi Kegagalan');
            

            // if($cek->)




        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }


        





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
    public function destroy(tandingM $tandingM, $idtanding)
    {
        try{
            $tanding = tandingM::where('idtanding', $idtanding)->where('ket2', 100);

            if($tanding->count() == 1) {
                $idlomba = $tanding->first()->idlomba;
                $idbagian = $tanding->first()->idbagian;
                $idkelas = $tanding->first()->idkelas;

                tandingM::where('idlomba', $idlomba)
                ->where('idbagian', $idbagian)
                ->where('idkelas', $idkelas)
                ->update([
                    'selesai' => false,
                ]);
            }
            $destroy = tandingM::where('idtanding', $idtanding)->delete();
                $destroy = pesertatandingM::where('idtanding', $idtanding)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
