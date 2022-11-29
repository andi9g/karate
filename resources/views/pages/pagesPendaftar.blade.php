@extends('layout.master')

@section('title')
    Perlombaan
@endsection

@section('activekuPendaftar')
    activeku
@endsection

@section('judul')
    <div class="container my-0 py-0">
        <h4 class="my-0 py-0">{{$namakelas}}</h4>

        <small class="badge badge-danger"> 
            <a href="{{ url('pendaftar', []) }}" class="text-white">
                Back
            </a>
        </small>
    </div>
@endsection

@section('content')
    
<div class="container">

    <div class="row mb-2">
        <div class="col-md-12">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#cetakberdasarkan">
                <i class="fa fa-print"></i> Cetak Berdasarkan
            </button>
            
            <!-- Modal -->
            <div class="modal fade" id="cetakberdasarkan" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cetak Berdasarkan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <form action="{{ route('cetak.filter', [$idkelas]) }}" method="post" target="_blank">
                            @csrf
                            <div class="modal-body">
                                <div class='form-group'>
                                    <select name='bagian' id='fornama' class='form-control'>
                                        <option value=''>Semua Kriteria</option>
                                        <option value="putra" @if ($bagian=='putra')
                                            selected
                                        @endif>PUTRA</option>
                                        <option value="putri" @if ($bagian=='putri')
                                            selected
                                        @endif>PUTRI</option>
                                    <select>
                                </div>
                                <div class='form-group'>
                                    <select name='lomba' id='fornama' class='form-control'>
                                        <option value=''>Semua Kejuaraan</option>
                                        @foreach ($datalomba as $dl)
                                            <option value="{{$dl->idlomba}}" @if ($lomba==$dl->idlomba)
                                                selected
                                            @endif>{{$dl->namalomba}}</option>
                                        @endforeach
                                    <select>
                                </div>
                                <div class='form-group'>
                                    <select name='sah' id='forsah' class='form-control'>
                                        <option value=''>Semua</option>
                                        <option value='1' @if ($sah==='1')
                                            selected
                                        @endif>Sah</option>
                                        <option value='none' @if ($sah=='0')
                                            selected
                                        @endif>Tidak Sah</option>
                                    <select>
                                </div>
    
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-print"></i> Cetak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ url()->current() }}" class="">
    <div class="row">
        <div class="col-md-7">
           <div class="row">
            <div class="col-md-4">
                <div class='form-group'>
                    <select name='bagian' onchange="submit()" id='fornama' class='form-control'>
                        <option value=''>Semua Kriteria</option>
                        <option value="putra" @if ($bagian=='putra')
                            selected
                        @endif>PUTRA</option>
                        <option value="putri" @if ($bagian=='putri')
                            selected
                        @endif>PUTRI</option>
                    <select>
                </div>
            </div>
            <div class="col-md-4">
                <div class='form-group'>
                    <select name='lomba' onchange="submit()" id='fornama' class='form-control'>
                        <option value=''>Semua Kejuaraan</option>
                        @foreach ($datalomba as $dl)
                            <option value="{{$dl->idlomba}}" @if ($lomba==$dl->idlomba)
                                selected
                            @endif>{{$dl->namalomba}}</option>
                        @endforeach
                    <select>
                </div>
            </div>
            <div class="col-md-4">
                <div class='form-group'>
                    <select name='sah' onchange="submit()" id='forsah' class='form-control'>
                        <option value=''>Semua</option>
                        <option value='1' @if ($sah==='1')
                            selected
                        @endif>Sah</option>
                        <option value='none' @if ($sah=='0')
                            selected
                        @endif>Tidak Sah</option>
                    <select>
                </div>
            </div>
           </div>
        </div>
        
        <div class="col-md-5">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" placeholder="nama atau kontingen" aria-describedby="button-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                    </div>
                </div>
                
        </div>
    </div>
    </form>



    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Kontingen</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        <th>Regu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pertandingan as $item)
                        <tr>
                            <td width=10px>{{$loop->iteration + $pertandingan->firstItem() -1 }}</td>
                            <td class="text-uppercase text-bold">{{$item->namapeserta}}</td>
                            <td>{{$item->kontingen}}</td>
                            <td class="text-bold text-uppercase">{{$item->namabagian}}</td>
                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-info badge-btn border-0" data-toggle="modal" data-target="#detail{{$item->idpertandingan}}">
                                <i class="fa fa-image"></i> Lihat Gambar
                                </button>
                            </td>
                            <td>
                                @if ($item->sah == true)
                                    @php
                                        $regu = DB::table('regu')->where('namaregu', 'like', 'Pool %')
                                        ->get();
                                    @endphp
                                    <div class='form-group m-0'>
                                        <form action="{{ route('kelola.regu', [$item->idpertandingan]) }}" method="post" class="d-inline">
                                            @csrf
                                            <select name='regu' title="{{ucwords($item->namapeserta)}}" id='forregu' onchange="submit()" required class='w-100 m-0' >
                                                <option value=''>Pilih Regu</option>
                                                @foreach ($regu as $r)
                                                    @php
                                                        $pesertatanding = DB::table('pesertatanding')
                                                        ->join('tanding', 'tanding.idtanding', 'pesertatanding.idtanding')
                                                        ->where('pesertatanding.idpertandingan', $item->idpertandingan)
                                                        ->where('tanding.idregu', $r->idregu)
                                                        ->count();
                                                    @endphp
                                                    <option value="{{$r->idregu}}" @if ($pesertatanding==1)
                                                        selected
                                                    @endif>{{$r->namaregu}}</option>
                                                @endforeach
                                            <select>

                                        </form>
                                    </div>
                                @else
                                    Belum ada regu
                                @endif
                                {{-- @php
                                    $data = DB::table('pesertatanding')
                                    
                                @endphp --}}
                            </td>
                            <td>
                                @if ($item->sah == false)
                                    <form action="{{ route('pendaftar.update', [$item->idpertandingan]) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="badge badge-success badge-btn border-0 w-100 text-bold">ACCEPT</button>
                                    </form>
                                @else
                                    <form action="{{ route('pendaftar.cancel', [$item->idpertandingan]) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="badge badge-secondary badge-btn border-0 w-100 text-bold">CANCEL</button>
                                    </form>
                                @endif

                                
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="detail{{$item->idpertandingan}}" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Gambar Peserta</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>
                                    <div class="modal-body text-center">

                                        <img src="{{$item->gambar}}" class="text-center rounded-lg mb-3" width="170px" alt="">
                                        <br>
                                        <h5>{{$item->wa}}</h5>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            {{$pertandingan->links('vendor.pagination.bootstrap-4')}}
        </div>
    </div>


</div>


@endsection


@section('script')
<script>
    $(".js-example-tags").select2({
      tags: true
    });

    
</script>
@endsection