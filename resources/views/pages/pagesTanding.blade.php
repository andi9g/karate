@extends('layout.master')

@section('title')
    Peserta Regu
@endsection

@section('activekuRegu')
    activeku
@endsection

@section('judul')
    <div class="container my-0 py-0">
        <h4 class="my-0 py-0">
            <i class="fa fa-layer-group"></i> Regu Peserta
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
                ->where(function ($query) use ($keyword) {
                    $query->where('namakelas', 'like', "%$keyword%");
                })
                ->orderBy('idkelas', 'asc')->get();
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
                                $bagian = DB::table('bagian')->orderBy('idbagian', 'asc')->get();
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
                                    ->count();
                                @endphp
                            <li class="nav-item">
                              <a href="{{ route('peserta.regu', [$l->idlomba, $k->idkelas, $b->idbagian]) }}" class="nav-link text-uppercase text-bold bg-light" style="color: rgb(53, 53, 53) !important">
                                {{$b->namabagian}} 
                                @if ($jumlah != 0)
                                    <span class="float-right badge bg-info">{{$jumlah}}</span>asd
                                @endif
                              </a>
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