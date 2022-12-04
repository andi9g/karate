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
        td {
            padding: 2px 5px;
        }
        </style>
</head>
<body>
    <h2 align="center">{{strtoupper("Peserta Kejuaraan")}}</h2>
    <div class="div">
        <table width="100%" border="1" style="border-collapse: collapse;border:1px solid black;padding: 2px;font-size:8pt">
            <tr>
                <th width="20px">NO</th>
                <th>NAMA PESERTA</th>
                <th>KONTINGEN</th>
            </tr>

            @foreach ($data as $d1)
                <tr>
                    <td colspan="3" style="font-size:12pt;background: rgb(252, 132, 132);font-weight: bold">{{$d1['namalomba']}}</td>
                </tr>

                @foreach ($d1['kelas'] as $k)
                    <tr>
                        <td colspan="3" style="font-size:9pt;background: rgb(231, 231, 231);font-weight: bold">
                            
                            {{strtoupper($k['namakelas'])}}</td>
                    </tr>

                    @foreach ($k['bagian'] as $b)
                        <tr>
                            <td colspan="3" style="font-weight: bold;background: rgb(136, 250, 136);" align="center">{{strtoupper($b['namabagian'])}}</td>
                        </tr>

                        @foreach ($b['regu'] as $r)
                            <tr>
                                <td colspan="3" style="font-weight: bold">{{$r['namaregu']}}</td>
                            </tr>

                            @foreach ($r['tanding'] as $item1)
                                @foreach ($item1 as $item)
                                <tr>
                                    <td align="center">{{$item->urutan}}</td>
                                    <td>{{$item->namapeserta}}</td>
                                    <td>{{$item->kontingen}}</td>
                                </tr>
                                    
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach

                @endforeach

                
            @endforeach

        </table>
    </div>

</body>
</html>