<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        h2 {
            margin: 0;
            padding: 0;
        }
        h3 {
            margin: 0;
            padding: 0;
        }
        p {
            margin: 0;
            padding: 0;
        }
        .table {
            width: 100%;
            padding: 0px 2px;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .center {
            text-align: center;
        }
        .no-border tr{
            border: none;
            text-align: left;
        }
        .no-border th {
            border: none;
            text-align: left;
        }
        table {
            border: none;
        }

    </style>
</head>
<body>
    <h2>PESERTA KEJUARAAN</h2>

    <table class="table" border="1">
        @php
            $judul = false;
        @endphp
        @foreach ($namakelas as $nk)
            @php
                $judul = true;
            @endphp
            <thead class="no-border">
                <tr class="no-border">
                    <th colspan="6"><br>{{strtoupper($nk->namakelas)}}</th>
                </tr>
                <tr class="no-border">
                    <th colspan="6">{{strtoupper($nk->namalomba)}} &nbsp;|&nbsp;
                        @if ($nk->sah==0)
                            Belum Sah 
                        @else
                            Telah Sah 
                        @endif
                    </th>
                </tr>
            </thead>
            @if ($judul === true)
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Kontingen</th>
                        <th>Pertandingan</th>
                        <th>Nama Regu</th>
                        <th style="width: fit-content">No. Urut</th>
                    </tr>
                </thead>
                @php
                    $judul = false;
                @endphp
            @endif
            

            <tbody>
                @php
                    $bagian = $nk->namabagian;
                    $lomba = $nk->idlomba;
                    $sah = $nk->sah;
                    $idkelas = $nk->idkelas;
                    // dd($bagian." ".$lomba." ".$sah." ".$idkelas); 
                    $pertandingan = DB::table('pertandingan')->join('kelas', 'kelas.idkelas', 'pertandingan.idkelas')
                    ->join('peserta', 'peserta.idpeserta', 'pertandingan.idpeserta')
                    ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
                    ->join('bagian', 'bagian.idbagian', 'pertandingan.idbagian')
                    ->where('lomba.ket', true)
                    ->where('pertandingan.idkelas', $nk->idkelas)
                    ->where(function ($query) use ($bagian) {
                        $query->where('bagian.namabagian', 'like', "$bagian%");
                    })->where(function ($query) use ($sah) {
                        $query->where('pertandingan.sah', 'like', "$sah%");
                    })->where(function ($query) use ($lomba) {
                        $query->where('lomba.idlomba', 'like', "$lomba%");
                    })->select('peserta.*','pertandingan.*', 'bagian.namabagian')
                    ->get();
                    // dd($pertandingan);
                @endphp
                @foreach ($pertandingan as $item)
                    <tr>
                        <td width="5px" class="center">{{$loop->iteration}}</td>
                        <td>{{ucwords($item->namapeserta)}}</td>
                        <td class="center">{{$item->kontingen}}</td>
                        <td style="width: fit-content" class="center">{{strtoupper($item->namabagian)}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach

                
            </tbody>
        @endforeach
    </table>
   

</body>
</html>