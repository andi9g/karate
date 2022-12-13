<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ranking</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .page-break {
            page-break-before: always;
        }

        h3 {
            margin: 0;
            padding: 0;
        }
        h2 {
            margin: 0;
            padding: 0;
        }
        h4 {
            margin: 0;
            padding: 0;
        }
        .center {
            text-align: center;
        }
        </style>
</head>
<body>
    
    <div>
        <div class="center">
            <h3>SCORING KATA</h3>
            <h3>{{$namalomba}}</h3>
            <h4>[ {{strtoupper($namakelas)}} ] - [ {{strtoupper($namabagian)}} ]</h4>

        </div>
    
        <table width="100%" border="1" style="border-collapse: collapse;border:1px solid black;padding: 2px;font-size:7pt">
            <tr>
                <th style="text-align:center;" rowspan="2" align="center">No</th>
                <th rowspan="2" colspan="2" class="text-uppercase">Nama Peserta</th>
                <th style="text-align:center;" colspan="{{$jumlahjuri}}">Juri</th>
                <th style="text-align:center;" rowspan="2">Rumus</th>
                <th style="text-align:center;" rowspan="2">Total</th>
                
            </tr>
            <tr>
                @for ($i=1;$i<= $jumlahjuri;$i++)
                    <th style="text-align:center;" width="30px">{{$i}}</th> 
                @endfor
            </tr>
    
            @foreach ($cetak as $c)
            <tr>
                <td colspan="{{($jumlahjuri+5)}}" style="background: lightgrey;font-weight: bold;font-size:8pt">
                    {{$c['regu']}}
                </td>
            </tr>
    
            @foreach ($c['data'] as $item)
                    <tr>
                        <td width="5px" rowspan="2" style="text-align:center;">{{$item["urutan"]}}</td>
                        <td rowspan="2" class="text-uppercase text-bold">
                            @if ($item['waktu']==true)
                                {{empty($item["namagroup"])?$item["namapeserta"]:$item["namagroup"]}}
                                    
                            @else
                                {{$item["namapeserta"]}}

                            @endif
                        </td>
                        <td class="text-center text-bold" style="font-weight:bold">TEC</td>
                        
                        @if ($item['view'] == true)
                        @for ($i=1;$i<= $jumlahjuri;$i++)
                            <td style="text-align:center;"><i class="fa fa-check"></i></td> 
                        @endfor
    
                        <td></td>
                        <td rowspan="2" class="text-lg"><i class="fa fa-check"></i></td>
                        @else
                        @foreach ($item['tec'] as $tec)
                            @if ($tec['ket']==false)
                                <td class="text-danger" style="text-align:center;color:red;"><del>{{$tec['nilai']}}</del></td>
                            @else 
                                <td style="text-align:center;">{{$tec['nilai']}}</td>
                            @endif
                        @endforeach
                            
                        <td>{{$item['tec_total']}} * 0.7 = {{$item['tec_total_rumus']}}</td>
                        <td rowspan="2" class="text-lg" align="center">{{$item['total']}}</td>
                        @endif
                        
                    </tr>
                    <tr>
                        <td class="text-center text-bold" style="font-weight: bold">ATH</td>
                        @if ($item['view'] == true)
    
                        @for ($i=1;$i<= $jumlahjuri;$i++)
                            <td style="text-align:center;"><i class="fa fa-check"></i></td> 
                        @endfor
    
                        {{-- <td><i class="fa fa-check"></i></td> --}}
                        @else
    
                        @foreach ($item['ath'] as $ath)
                            @if ($ath['ket']==false)
                                <td class="text-danger" style="text-align:center;color:red;"><del>{{$ath['nilai']}}</del></td>
                            @else 
                                <td style="text-align:center;">{{$ath['nilai']}}</td>
                            @endif
                        @endforeach
                        <td>{{$item['ath_total']}} * 0.3 = {{$item['ath_total_rumus']}}</td>
                        @endif
                        
                    </tr>
                        
                    @endforeach
                
            @endforeach
    
            <tr>
                <td colspan="{{($jumlahjuri+5)}}" style="background: lightgrey;font-weight: bold;text-align: center">
                    HASIL PERTANDINGAN
                </td>
            </tr>
            <tr style="font-weight: bold">
                <td align="center">#</td>
                <td>NAMA JUARA</td>
                <td colspan="{{$jumlahjuri + 3}}">KONTINGEN</td>
            </tr>
            @foreach ($datafinal as $item)
                <tr>
                    <td align="center">{{($item->urutan==4)?3:$item->urutan}}</td>
                    <td>
                        @if ($item->waktu==true)
                                {{empty($item->namagroup)?$item->namapeserta:$item->namagroup}}
                                    
                            @else
                                {{$item["namapeserta"]}}

                            @endif

                    </td>
                    <td colspan="{{$jumlahjuri + 3}}">{{$item->kontingen}}</td>
                </tr>
            @endforeach
        </table>
    </div>

</body>
</html>