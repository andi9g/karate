@extends('layout.master')

@section('title')
    Perlombaan
@endsection

@section('activekuRegu')
    activeku
@endsection

@section('judul')
    <div class="container my-0 py-0">
        <div class="row">
        </div>

        <small class="badge badge-danger"> 
            <a href="{{ url('regu', []) }}" class="text-white">
                Back
            </a>
        </small>
    </div>
@endsection

@section('content')
    
<div class="container">

    <div class="row mb-2">
        <div class="col-md-12"></div>
    </div>

    <form action="{{ route('cari.regu', [$idlomba, $idkelas, $idbagian]) }}" class="" method="post">
        @csrf
    <div class="row">
        <div class="col-md-8">
           <div class="row">
            <div class="col-md-2">
                <div class='form-group'>
                    <select name='bagian' onchange="submit()" id='fornama' class='form-control'>
                        <option value=''>Semua Kriteria</option>
                        <option value="l" @if ($bagian=='putra')
                            selected
                        @endif>PUTRA</option>
                        <option value="p" @if ($bagian=='putri')
                            selected
                        @endif>PUTRI</option>
                    <select>
                </div>
            </div>
            <div class="col-md-5">
                <div class='form-group'>
                    <select name='kelas' id='forkelas' onchange="submit()" class='form-control'>
                        @foreach ($kelas as $k)
                            <option value="{{$k->idkelas}}" @if ($idkelas == $k->idkelas)
                                selected
                            @endif>{{$k->namakelas}}</option>
                        @endforeach
                    <select>
                </div>
            </div>
            <div class="col-md-5">
                <div class='form-group'>
                    <select name='lomba' id='forlomba' onchange="submit()" class='form-control'>
                        @foreach ($lomba as $l)
                            <option value="{{$l->idlomba}}" @if ($idlomba == $l->idlomba)
                                selected
                            @endif>{{$l->namalomba}}</option>
                        @endforeach
                    <select>
                </div>
            </div>
           </div>
        </div>
        
        <div class="col-md-4">
                
                
        </div>
    </div>
    </form>
    <form action="{{ url()->current() }}">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class='form-group'>
                        <select name='regu' onchange="submit()" id='forregu' class='form-control'>
                            <option value=''>Semua Pool</option>
                            @foreach ($regu as $r)
                                <option value="{{$r->idregu}}" @if ($r->idregu == $regu_get)
                                    selected
                                @endif>{{$r->namaregu}}</option>
                            @endforeach
                        <select>
                    </div>

                </div>
            </div>
        </div>
            <div class="col-md-4">
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
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Kontingen</th>
                        <th>Regu</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pesertatanding as $item)
                    <tr>
                        <td width="5px">{{$loop->iteration}}</td>
                        <td class="text-bold">{{ucwords($item->namapeserta)}}</td>
                        <td>{{$item->kontingen}}</td>
                        <td class="text-bold text-center">{{$item->namaregu}}</td>
                        <td class="text-center text-bold">
                            @php
                                $dataurutan = DB::table('pesertatanding')
                                ->join('pertandingan', 'pertandingan.idpertandingan', 'pesertatanding.idpertandingan')
                                ->where('pesertatanding.idtanding', $item->idtanding)
                                ->where('pertandingan.sah', true)
                                ->count();
                            @endphp

                            <form action="{{ route('regu.update', [$item->idpesertatanding]) }}" method="post" class="d-inline">
                                @csrf
                                @method('PUT')
                                <select name='urutan' title="{{ucwords($item->namapeserta)}}" id='forurutan' class='w-100' onchange="submit()" >
                                    <option value=''>Pilih</option>
                                    @for ($i=1;$i<=$dataurutan;$i++)
                                        <option value="{{$i}}" @if ($i == $item->urutan)
                                            selected
                                        @endif>{{$i}}</option>
                                    @endfor
                                    
                                </select>
                            </form>
                        </td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="badge badge-info badge-btn border-0 d-inline" data-toggle="modal" data-target="#edit{{$item->idpesertatanding}}">
                              <i class="fa fa-edit"></i> Edit
                            </button>
                            
                            <form action="{{ route('regu.destroy', [$item->idpesertatanding]) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-danger badge-btn border-0" data-toggle="modal" data-target="#hapus{{$item->idpesertatanding}}">
                                  <i class="fa fa-trash"></i>
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="hapus{{$item->idpesertatanding}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title">Peringatan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                            </div>
                                            <div class="modal-body">
                                                Yakin ingin menghapus data ini? 
                                                <br>
                                                data peserta lomba akan dinyatakan tidak mengikuti perlombaan.
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-success" data-dismiss="modal">NO</button>
                                                <button type="submit" onclick="return confirm('Lanjutkan proses?')" class="btn btn-danger">YA</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                    

                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            {{-- {{$pertandingan->links('vendor.pagination.bootstrap-4')}} --}}
        </div>
    </div>


</div>


@endsection

