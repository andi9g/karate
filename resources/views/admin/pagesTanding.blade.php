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
            <i class="fa fa-layer-group"></i> Peserta Tanding
        </h4>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-7"></div>
        <div class="col-md-5">
            <form action="{{ url()->current() }}" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" placeholder="Nama Kelas" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                </div>
            </form>
        </div>
        </div>
    </div>


    @foreach ($lomba as $l)
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="text-bold" style="border-bottom: 2px dashed grey">{{$l->namalomba}}</h4>
            @php
                $kelas = DB::table('kelas')
                ->join('tanding', 'tanding.idkelas', 'kelas.idkelas')
                ->join('lomba', 'lomba.idlomba', 'tanding.idlomba')
                ->join('pesertatanding', 'pesertatanding.idtanding', 'tanding.idtanding')
                ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
                ->join('bagian', 'bagian.idbagian', 'tanding.idbagian')
                ->where(function ($query) use ($keyword) {
                    $query->where('kelas.namakelas', 'like', "%$keyword%");
                })
                ->groupBy('kelas.idkelas')
                ->groupBy('kelas.namakelas')
                ->orderBy('idkelas', 'asc')
                ->select('kelas.idkelas', 'kelas.namakelas')
                ->get();
            @endphp
            <div class="row">
                @php
                    $j= 1;        
                @endphp
                @foreach ($kelas as $k)
                <div class="col-md-4">
                    @php
                        $arr = ['Warna','bg-gradient-secondary','bg-gradient-green', 'bg-gradient-danger']; 
                    @endphp
                    <div class="card">
                        <div class="card-header p-2 {{$arr[$j]}}">
                          <h6 class="m-0 p-0 text-bold text-capitalize">{{$k->namakelas}}</h6>
                        </div>
                        @php
                            $j++;
                            if($j > 3) {
                                $j = 1;
                            }
                        @endphp
                        <div class="card-footer p-0">
                            @php
                                $bagian = DB::table('bagian')
                                ->join('tanding', 'tanding.idbagian', 'bagian.idbagian')
                                ->groupBy('bagian.idbagian')
                                ->groupBy('bagian.namabagian')
                                ->orderBy('bagian.idbagian', 'asc')
                                ->select('bagian.idbagian', 'bagian.namabagian')
                                ->get();
                            @endphp
                          <ul class="nav flex-column">
                            @foreach ($bagian as $b)
                                @php
                                    $jumlah = DB::table('pesertatanding')
                                    ->join('tanding', 'tanding.idtanding', 'pesertatanding.idtanding')
                                    ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
                                    ->where('tanding.idkelas', $k->idkelas)
                                    ->where('tanding.idbagian', $b->idbagian)
                                    ->where('tanding.idlomba', $l->idlomba)
                                    ->where('pertandingan.sah', true)
                                    ->where('pesertatanding.urutan', '!=', null)
                                    ->count();
                                @endphp
                            <li class="nav-item">
                                @php
                                    $tanding = DB::table('tanding')
                                    ->where('idlomba', $l->idlomba)
                                    ->where('idbagian', $b->idbagian)
                                    ->where('idkelas', $k->idkelas)
                                    ->where('idadmin', null)
                                    ->where('idlapangan', null)
                                    ->count();
                                @endphp

                                @if ($tanding > 0)
                                    <!-- Button trigger modal -->
                                    <a type="button"  class="nav-link text-uppercase text-bold bg-light" data-toggle="modal" data-target="#pilih{{$l->idlomba.$k->idkelas.$b->idbagian}}" style="color: rgb(73, 73, 73) !important">
                                        {{$b->namabagian}} 
                                        @if ($jumlah != 0)
                                            <span class="float-right badge bg-info">{{$jumlah}}</span>
                                        @endif
                                    </a>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="pilih{{$l->idlomba.$k->idkelas.$b->idbagian}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title ">KETENTUAN</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                                <form action="{{ route('pilih.tanding', [$l->idlomba,$b->idbagian,$k->idkelas]) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                
                                                    <div class="modal-body">
                                                        <div class='form-group'>
                                                            <label for='foridadmin' class='text-capitalize'>Id Admin</label>
                                                            <input type='text' name='idadmin' id='foridadmin' class='form-control' placeholder='masukan namaplaceholder' value="{{$idadmin}}" readonly>
                                                        </div>

                                                        <div class='form-group'>
                                                            <label for='fornamaadmin' class='text-capitalize'>Nama Admin</label>
                                                            <input type='text' name='namaadmin' id='fornamaadmin' class='form-control' placeholder='masukan namaplaceholder' value="{{$namaadmin}}" readonly>
                                                        </div>

                                                        <div class='form-group'>
                                                            <label for='forwaktulomba' class='text-capitalize'>Waktu Lomba</label>
                                                            <select name='waktulomba' id='forwaktulomba' class='form-control'>
                                                                <option value='none'>Tidak Menggunakan Waktu</option>
                                                                <option value='true'>5 Menit</option>
                                                            <select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" onclick="" class="btn btn-primary">Pilih Tanding  </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else 

                                @php
                                    $tanding2 = DB::table('tanding')
                                    ->where('idlomba', $l->idlomba)
                                    ->where('idbagian', $b->idbagian)
                                    ->where('idkelas', $k->idkelas)
                                    ->where('idadmin', $idadmin)
                                    ->where('idlapangan', $idlapangan)
                                    ->count();
                                @endphp
                                    @if ($tanding2 > 0)
                                        <a href="{{ route('pilih.bagian', [$l->idlomba, $b->idbagian, $k->idkelas]) }}" class="nav-link text-uppercase text-bold bg-light" style="color: rgb(73, 73, 73) !important;background: rgba(198, 253, 198, 0.5) !important">
                                            {{$b->namabagian}} 
                                            @if ($jumlah != 0)
                                                <span class="float-right badge bg-info">{{$jumlah}}</span>
                                            @endif
                                        </a>
                                    @endif

                                @endif

                              
                            </li>
                            @endforeach
                            
                            
                          </ul>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>


                
              
        </div>
    </div>
    @endforeach
</div>


@endsection


@section('script')
<script>
    $(".js-example-tags").select2({
      tags: true
    });

    
</script>
@endsection