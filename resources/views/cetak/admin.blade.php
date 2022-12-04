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
    <h2>Data Login</h2>
    <h3>Admin & Juri</h3>
    <div class="div">
        <table width="100%" border="1" style="border-collapse: collapse;border:1px solid black;padding: 2px;font-size:10pt">
            <tr>
                <th width="20px">No</th>
                <th>Username</th>
                <th>Password</th>
                <th>Posisi</th>
            </tr>

            @php
                $i = 1;
            @endphp
            @foreach ($admin as $a)
                <tr>
                    <td colspan="4" style="background: rgb(236, 236, 236);font-weight: bold">DATA ADMIN</td>
                </tr>
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$a['username']}}</td>
                    <td align="center" >{{$a['password']}}</td>
                    <td>{{$a['namalapangan']}}</td>
                </tr>

                @if (count($a['juri'])>0)
                <tr>
                    <td colspan="4" style="background: rgb(236, 236, 236);font-weight: bold">DATA JURI</td>
                </tr>
                @endif
                @foreach ($a['juri'] as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->username}}</td>
                        <td align="center" >{{$item->password2}}</td>
                        <td>Juri {{$item->posisi}}</td>
                    </tr>
                    
                @endforeach
                @php
                    $i++;
                @endphp
            @endforeach

        </table>
    </div>

</body>
</html>