@extends('layout.master')

@section('title')
    PENILAIAN
@endsection

@section('activekunilai')
    activeku
@endsection

@section('headers')
    @livewireStyles()
@endsection
@section('footers')
    @livewireScripts()
@endsection

@section('content')
@php
    $nila_pilih = [];
            for($i=50;$i<=100;$i =$i+2){
                if ($i % 2 ==0) {
                    if($i==100) {
                        $nilai_pilih[] = substr($i, 0,2).".".substr($i, 2,1); 
                    }else {
                        $nilai_pilih[] = substr($i, 0,1).".".substr($i, 1,1); 
                    }
                }
            }

@endphp
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        @foreach ($data as $peserta)
            @livewire('refresh-juri', [
                'idpesertatanding' => $peserta->idpesertatanding,
                'idtanding' => $peserta->idtanding,
            ])
            @php
                $cek = DB::table('penilaian')->join('pesertatanding', 'pesertatanding.idpesertatanding', 'penilaian.idpesertatanding')
                ->select('penilaian.*')
                ->where('penilaian.idpesertatanding', $peserta->idpesertatanding)
                ->where('pesertatanding.selesai', false)
                ->where('penilaian.idjuri', $idjuri);

                $jml = $cek->count();
                $nt = "";
                $na = "";
                if($jml == 1) {
                    $nt = $cek->first()->nt;
                    $na = $cek->first()->na;;
                }
                
            @endphp

            @section('judul')
            <div class="container my-0 py-0">
                <h4 class="my-0 py-0 text-bold">
                    <i class="fa fa-edit"></i>{{strtoupper($peserta->namakelas)}}
                </h4>
            </div>
            @endsection

            <h5 class="text-bold">
            <br>
            <font class="text-success">
                {{strtoupper(str_replace('Lapangan', 'Tatami', $peserta->namalapangan))}}
            </font>
            </h5>

                <div class="card mb-5">
                    <div class="card-header" >
                        <h4 class="m-0 d-inline">
                            [ <b>{{$peserta->urutan}}</b> ] 
                             
                                <b>
                                    {{$peserta->namaregu}}
                                    @if ($peserta->ket != "primary")
                                        {{$peserta->ket}}
                                    @endif
                                </b>
                        </h4>
                        
                    </div>
                    <form action="{{ route('nilai.menilai', [$peserta->idpesertatanding, $idjuri, $idlapangan]) }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img src="{{ url('/img', ['gambar.jpg']) }}" width="140px" class="rounded-lg" alt="">
                                </div>
                                <div class="col-md-9">
                                    <table class="text-lg my-0 py-0" width="100%" border="0">
                                        <tr>
                                            <td nowrap width="20%" valign="top">Nama Peserta &nbsp;</td>
                                            <td colspan="2" valign="top"> : </td>
                                            <td valign="top"><b>{{$peserta->namapeserta}}</b></td>
                                        </tr>
                                        <tr >
                                            <td valign="top">Kontingen</td>
                                            <td valign="top" colspan="2"> : </td>
                                            <td valign="top">{{$peserta->kontingen}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <table class="w-100 mt-2">
                                                    <tr>
                                                        <td colspan="2" class="text-center text-bold" style="background: rgb(212, 212, 212)">
                                                            PENILAIAN
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="text-center bg-danger text-bold w-100" for="tec">TEC

                                                                    </label>
                                                                    <select name="tec" required id="tec" @if ($jml==1)
                                                                        disabled
                                                                    @endif class="w-100 py-2 text-center" style="border: 0;border-bottom: 1px solid lightgrey;border-left: 1px solid lightgrey;border-right: 1px solid lightgrey;background: none;outline: none">
                                                                            <option value="">Click Here!</option>
                                                                            <option value="0.0">0.0</option>
                                                                            @foreach ($nilai_pilih as $np)
                                                                                <option value="{{$np}}" @if ($nt==$np)
                                                                                    selected
                                                                                @endif>{{$np}}</option>
                                                                            @endforeach
                                                                </select> 

                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="text-center bg-success text-bold w-100" for="athfor">ATH</label>
                                                                    <select name="ath" @if ($jml == 1)
                                                                        disabled
                                                                    @endif required id="athfor" class="w-100 py-2 text-center" style="border: 0;border-bottom: 1px solid lightgrey;border-left: 1px solid lightgrey;border-right: 1px solid lightgrey;background: none;outline: none">
                                                                        <option value="">Click Here!</option>
                                                                        <option value="0.0">0.0</option>
                                                                        @foreach ($nilai_pilih as $np)
                                                                            <option value="{{$np}}" @if ($na == $np)
                                                                                selected
                                                                            @endif>{{$np}}</option>
                                                                        @endforeach
                                                                    </select> 

                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    

                                                </table>
                                            </td>
                                        </tr>
                                        

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer m-0 p-0">
                            <!-- Button trigger modal -->
                            @if ($jml == 0)
                            <button type="button" class="btn btn-danger btn-block btn-lg rounded-0" data-toggle="modal" data-target="#nilai{{$peserta->idpesertatanding}}">
                                <b>
                                    PROCESS
                                </b>
                            </button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="nilai{{$peserta->idpesertatanding}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title">Alert!</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                        <div class="modal-body">
                                            <h4>Yakin dengan keputusan anda?
                                                <br>
                                                <i>Confident with your decision?</i>
                                            </h4>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                                            <button type="submit" class="btn btn-primary">YES</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                            @else
                            <button type="button" disabled class="btn btn-danger btn-block btn-lg rounded-0">
                                <b>
                                    TELAH DINILAI
                                </b>
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            
        @endforeach

        @if (count($data) == 0)
            <div class="row">
                <div class="col-12 text-center">
                    @livewire('refresh-juri2')
                    <div class="card">
                        <h1 class="my-0 text-capitalize">waiting for the next participant</h1>
                    </div>
                </div>
            </div>
        @endif
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