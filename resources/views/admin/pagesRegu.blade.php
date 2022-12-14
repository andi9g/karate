@extends('layout.master')

@section('title')
    Peserta Lomba
@endsection

@section('activekutanding')
    activeku
@endsection

@section('judul')
    <div class="container my-0 py-0">
        <h4 class="my-0 py-0">
            <i class="fa fa-layer-group"></i> {{$namalomba}} <br>
            <a href="{{ url('tanding', []) }}" class="btn btn-danger">
                <i class="fa fa-arrow-alt-circle-left"></i> Kembali
            </a>
        </h4>
    </div>
@endsection

@section('content')
 <!-- Modal -->
 <div class="modal fade" id="finish" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengambilan Kejuaraan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{ route('finish.tanding', [$idlomba,$idbagian,$idkelas]) }}" method="post">
                @csrf
            
            <div class="modal-body">
                <p class="text-lg">
                    Harap diingat, jika menekan button proses maka akan dilakukan finishing untuk mendapatkan hasil kejuaraan 1,2,3 dan 3

                </p>
                <p class="text-danger text-bold">
                    pastikan pertandingan telah dinilai lalu di proses!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Proses</button>
            </div>
        </form>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-secondary btn-sm mb-2" data-toggle="modal" data-target="#finish">
              <i class="fa fa-check"></i> Finishing
            </button>
            
            
            @if ($finish == 1)
                <a href="{{ route('tanding.cetak', [$idlomba,$idbagian,$idkelas]) }}" target="_blank" class="btn btn-success btn-sm mb-2">
                    <i class="fa fa-eye"></i> Review Kejuaraan
                </a>
            @endif

            
            <form action="{{ route('kelompok.tanding', [$idlomba,$idbagian,$idkelas]) }}" method="post">
                @csrf
            <div class="card">
                <div class="card-header p-2 bg-gradient-secondary">
                    <h6 class="m-0 p-0 text-bold text-uppercase">{{$kelas}} &nbsp;&nbsp; | &nbsp;&nbsp; {{$bagian}}</h6>
                </div>
                <div class="card-body p-0">
                    <table class="" style="border-collapse: collapse;width:100%">
                        @foreach ($regu as $item)
                        @php
                            $idpesertatanding = DB::table('tanding')
                            ->join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
                            ->where('tanding.idlomba', $idlomba)
                            ->where('tanding.idbagian', $idbagian)
                            ->where('tanding.idkelas', $idkelas)
                            ->where('tanding.idregu', $item->idregu)
                            ->where('pesertatanding.urutan', '!=', null)
                            ->orderBy('pesertatanding.urutan', 'desc')
                            ->select('pesertatanding.idpesertatanding')
                            ->first();
                            
                            $idpesertatanding = empty($idpesertatanding->idpesertatanding)?0:$idpesertatanding->idpesertatanding;

                            $jumlahPeserta = DB::table('pesertatanding')
                            ->where('idtanding', $item->idtanding)
                            ->count();
                            
                            $indikator = DB::table('pesertatanding')
                            ->where('idtanding', $item->idtanding)
                            ->where('selesai', true)
                            ->count();
                
                        @endphp

                        <tr style="border-bottom: 0.5px solid grey">
                            
                            <td class="text-center">
                                {{-- @if ($indikator >= $jumlahPeserta) --}}
                                    <input type="checkbox" name="idtanding[]" value="{{$item->idtanding}}">
                                {{-- @endif --}}
                            </td>
                            <td width="70%">
                                <ul class="nav flex-column">
                                    @php
                                        $ket2 = empty($item->ket2)?'none':$item->ket2;
                                    @endphp
                                    <li class="nav-item">
                                        @php
                                            $jumlahPeserta = DB::table('pesertatanding')
                                            ->where('idtanding', $item->idtanding)
                                            ->count();
                                        @endphp
                                        <a href="{{ route('pilih.regu', [$idlomba,$idbagian, $idkelas, $item->idregu, $item->idtanding]) }}" class="nav-link text-bold bg-light" style="color: rgb(73, 73, 73) !important">
                                            {{ucwords($item->namaregu)}} {{($item->ket=='primary')?"":$item->ket}} 
                                            &emsp;
                                            <small class="badge badge-info">{{$jumlahPeserta}}</small>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-center">
                                @if ($indikator >= $jumlahPeserta)
                                    <i class="fa fa-circle text-success" title="telah dinilai"></i>
                                
                                @else
                                    <i class="fa fa-circle text-danger" title="belum dinilai"></i>

                                @endif

                                &nbsp;
                                &nbsp;
                                [ {{$item->index}} ]
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-primary border-0" data-toggle="modal" data-target="#ubahIndex{{$item->idtanding}}">
                                  <i class="fa fa-edit"></i>
                                </button>

                                @php
                                    
                                @endphp

                                @php
                                    $cekTanding = DB::table('tanding')->join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
                                    ->join('penilaian','penilaian.idpesertatanding', 'pesertatanding.idpesertatanding')
                                    ->where('tanding.idtanding', $item->idtanding)
                                    ->count();
                                @endphp

                                @if ($item->ket != 'primary')
                                <!-- Button trigger modal -->
                                    @if ($cekTanding < $jumlahjuri)
                                    <button type="button" class="badge badge-danger border-0" data-toggle="modal" data-target="#hapusIndex{{$item->idtanding}}">
                                        <i class="fa fa-trash"></i>
                                      </button>
                                    @endif
                                
                                    
                                @endif
                                
                               
                                
                                
                                
                            </td>
                        </tr>
                        
                        @endforeach
                    </table> 
                </div>
            </div>


            <div class="card">
                @if ($finish == 1)
                    <div class="card-body text-center">
                        <h1 class="text-success">FINISH</h1>
                    </div>
                @else
                
                <div class="card-body m-1 p-1">
                    @php
                        $regu_ = DB::table('regu')
                        ->where('namaregu', 'like', 'Babak%')
                        ->orWhere('namaregu', 'like', 'Final%')
                        ->get();
                    @endphp
                    <div class='form-group mb-0 pb-0'>
                        <small>Harap Ceklis centang lalu pilih</small>
                        <select name='regu' required id='forregu' class='form-control mb-0'>
                            <option value=''>Pilih</option>
                            @foreach ($regu_ as $r)
                            <option value='{{$r->idregu}}'>{{$r->namaregu}}</option>
                                
                            @endforeach
                        <select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" onclick="return confirm('yakin ingin diproses?')" class="btn btn-success btn-block">
                        Proses
                    </button>
                </div>

                @endif
            </div>
            

            
            </form>

        </div>
        <div class="col-md-3"></div>
    </div>
</div>


@foreach ($regu as $item)
    <!-- Modal -->
    <div class="modal fade" id="ubahIndex{{$item->idtanding}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Posisi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form action="{{ route('ubah.urutan', [$item->idtanding]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class='form-group'>
                            <label for='forindex' class='text-capitalize'>Index Urutan</label>
                            <select name='index' id='forindex' class='form-control'>
                                <option value=''>Pilih</option>
                                @for ($i = 1; $i <= $jumlah; $i++)
                                    <option value="{{$i}}" @if ($item->index == $i)
                                        selected
                                    @endif>
                                        {{$i}}
                                </option>
                                    
                                @endfor
                            <select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Ubah Urutan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Modal -->
     <div class="modal fade" id="hapusIndex{{$item->idtanding}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Hapus Pertandingan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form action="{{ route('hapus.tanding.utama', [$item->idtanding]) }}" method="post" class="d-inline">
                    @csrf
                    @method('DELETE')
                <div class="modal-body">
                    <h4>Yakin ingin menghapus data?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return confirm('lanjutkan proses hapus')" class="btn btn-primary">Setuju</button>
                </div>
            </form>
            </div>
        </div>
    </div>


@endforeach



@endsection


@section('script')
<script>
    $(".js-example-tags").select2({
      tags: true
    });

    
</script>
@endsection