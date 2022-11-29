@extends('layout.master')

@section('title')
    Perlombaan
@endsection

@section('activekuPendaftar')
    activeku
@endsection

@section('content')
    
<div class="container">
    
    @foreach ($pertandingan as $item)
    <div class="row mb-2">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="list-group">
            <a href="{{ url('pendaftar/'.$item->idkelas.'/list') }}" class="list-group-item list-group-item-action text-bold text-uppercase">
                {{$item->namakelas}}
                    &nbsp;
            <span class="badge badge-primary badge-pill">
                @php
                    $jumlah = DB::table('kelas')->join('pertandingan','pertandingan.idkelas', 'kelas.idkelas')
                    ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
                    ->where('kelas.idkelas', $item->idkelas)
                    ->count();
                @endphp

                {{$jumlah}}
            </span>
            </a>
        
            </div>
        </div>
    </div>
        
    @endforeach

    @if (count($pertandingan)==0)
    <div class="row">
        <div class="col-12 text-center" >
            <h3>
                Tidak ada ditemukan
            </h3>
        </div>
    </div>
    @endif

</div>


@endsection


@section('script')
<script>
    $(".js-example-tags").select2({
      tags: true
    });

    
</script>
@endsection