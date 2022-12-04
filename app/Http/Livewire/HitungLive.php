<?php

namespace App\Http\Livewire;

use Livewire\Component;
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
use ArrayObject;
class HitungLive extends Component
{
    public $nilai,$idpesertatanding,$idtanding;

    
    public function render()
    {

        return view('livewire.hitung-live');
    }


    public function hitung(Request $request)
    {
        // dd($this->idtanding);
        $cek = tandingM::where('idtanding', $this->idtanding)
        ->where('selesai', true)->count();

        if($cek == 1) {
            return redirect('monitor')->with('success', 'Success');
        }
        $idadmin = $request->session()->get('idadmin');
        $idlapangan = adminM::where('idadmin', $idadmin)->first()->idlapangan;

        $jumlahjuri = pengaturanM::first()->jumlahjuri;

        $peserta = tandingM::join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
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
        ->where('pesertatanding.idpesertatanding', $this->idpesertatanding)
        ->where('pesertatanding.selesai', false)
        ->orderBy('tanding.index', 'asc')
        ->orderBy('pesertatanding.urutan', 'asc')
        ->select('tanding.*', 'peserta.namapeserta', 'peserta.gambar', 'pesertatanding.idpesertatanding', 'pesertatanding.urutan', 'kelas.namakelas', 'bagian.namabagian', 'regu.namaregu','peserta.kontingen', 'lapangan.namalapangan')
        ->take(1)->get();

        
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
            // dd($nilaiNT);
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

            $this->nilai = (string)$total;
            
            
        }
        
    }
}
