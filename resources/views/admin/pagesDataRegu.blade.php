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
            <i class="fa fa-layer-group"></i> 
        </h4>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="card table-responsive">
        <table class="table table-striped table-bordered table-sm" >
            <thead valign="top">
                <tr>
                    <th class="text-center" rowspan="2" align="center">No</th>
                    <th rowspan="2" colspan="2" class="text-uppercase">Nama Peserta</th>
                    <th class="text-center" colspan="{{$jumlahjuri}}">Juri</th>
                    <th class="text-center" rowspan="2">Rumus</th>
                    <th class="text-center" rowspan="2">Total</th>
                    
                </tr>
                <tr>
                    @for ($i=1;$i<= $jumlahjuri;$i++)
                        <th class="text-center">{{$i}}</th> 
                    @endfor
                </tr>

            </thead>

            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td width="5px" rowspan="2" class="text-center">{{$item["urutan"]}}</td>
                    <td rowspan="2" class="text-uppercase text-bold">{{$item["namapeserta"]}}</td>
                    <td class="text-center text-bold">TEC</td>
                    
                    @if ($item['view'] == true)
                    @for ($i=1;$i<= $jumlahjuri;$i++)
                        <td class="text-center"><i class="fa fa-check"></i></td> 
                    @endfor

                    <td></td>
                    <td rowspan="2" class="text-lg"><i class="fa fa-check"></i></td>
                    @else
                    @foreach ($item['tec'] as $tec)
                        @if ($tec['ket']==false)
                            <td class="text-danger"><del>{{$tec['nilai']}}</del></td>
                        @else 
                            <td>{{$tec['nilai']}}</td>
                        @endif
                    @endforeach
                        
                    <td>{{$item['tec_total']}} * 0.7 = {{$item['tec_total_rumus']}}</td>
                    <td rowspan="2" class="text-lg">{{$item['total']}}</td>
                    @endif
                    
                </tr>
                <tr>
                    <td class="text-center text-bold">ATH</td>
                    @if ($item['view'] == true)

                    @for ($i=1;$i<= $jumlahjuri;$i++)
                        <td class="text-center"><i class="fa fa-check"></i></td> 
                    @endfor

                    {{-- <td><i class="fa fa-check"></i></td> --}}
                    @else

                    @foreach ($item['ath'] as $ath)
                        @if ($ath['ket']==false)
                            <td class="text-danger"><del>{{$ath['nilai']}}</del></td>
                        @else 
                            <td>{{$ath['nilai']}}</td>
                        @endif
                    @endforeach
                    <td>{{$item['ath_total']}} * 0.3 = {{$item['ath_total_rumus']}}</td>
                    @endif
                    
                </tr>
                    
                @endforeach
                {{-- {{dd($nilai_tec)}} --}}
            </tbody>


        </table>
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