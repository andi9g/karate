<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('tittle')</title>

    @include('layout.header')
    @livewireStyles
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            /* font-family: Arial, Helvetica, sans-serif !important; */
        }

        .bgku {
            background: url("{{ url('/img/tampil/OPEN2.jpg', []) }}");

        }

        .bawah {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .waktu {
            position: fixed;
            width: fit-content !important;
            bottom: 10px;
            /* left: 90px; */
            right: 10px;
            z-index: 100;
        }

        .back {
            position: fixed;
            bottom: 10px;
            right: 10px;
            z-index: 100;
        }
        .input-waktu {
            font-size: 70px;
            background: none;
            border:none;
            text-align: center;
            width: 230px;
        }

        div.timer {
            border:1px #666666 solid;
            width:190px;
            height:50px;
            background: white;
            line-height:50px;
            font-size:36px;
            font-family:"Courier New", Courier, monospace;
            text-align:center;
            margin:5px;
        }
    </style>
</head>
<body class="bgku">
    <br>
    
    @yield('content')
    @include('layout.script')
    @livewireScripts

    

    @yield('myScript')
</body>
</html>