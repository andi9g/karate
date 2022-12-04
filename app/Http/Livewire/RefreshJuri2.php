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

class RefreshJuri2 extends Component
{
    public function render()
    {
        return view('livewire.refresh-juri2');
    }

    public function refresh(Request $request)
    {
        $idadmin = $request->session()->get('idadmin');
        $idlapangan = $request->session()->get('idlapangan');
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

        if(count($data) == 1) {
            return redirect('nilai')->with('success', 'Open Point');
        }
    }
}
