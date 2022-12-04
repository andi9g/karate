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

class RefreshJuri extends Component
{
    public $idpesertatanding,$idtanding;
    
    public function render()
    {
        return view('livewire.refresh-juri');
    }

    public function refresh(Request $request)
    {
        $cek = pesertatandingM::where('idpesertatanding', $this->idpesertatanding)
        ->where('selesai', true)->count();
        $cek2 = tandingM::where('idtanding', $this->idtanding)
        ->where('selesai', true)->count();
        // dd($cek);
        if($cek == 1) {
            return redirect('nilai')->with('success', 'Peserta Berikutnya');
        }

        if($cek2 == 1) {
            return redirect('nilai')->with('success', 'Selesai');
        }
    }
}
