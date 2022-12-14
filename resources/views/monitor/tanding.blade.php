@extends('layout.monitor')

@section('tittle')
    Monitor
@endsection

@section('content')
@foreach ($data as $item)
<form action="{{ route('monitor.selesai', [$item->idpesertatanding,$item->waktu]) }}" method="post">
    @csrf

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card text-center rounded-0 ">
                <div class="card-header rounded-0 bg-secondary">
                    <h3 class="text-uppercase my-0 text-bold">{{$item->namakelas}}</h3>
                </div>
                <div class="card-footer py-1 rounded-0">
                    <h4 class="text-uppercase my-0 py-0">
                        {{ str_replace('Lapangan', 'Tatami', $item->namalapangan) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    @if ($item->waktu == true)
    <div class="waktu">
        <div class="bg-white p-2 rounded-top">
            <input type="text" name="waktu" value="05:00" id="waktu" class="input-waktu">
        </div>
        <div class="bg-white p-2 rounded-0">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-block btn-danger" onClick="timer.stop()">Stop</button>

                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-block btn-success" onClick="timer.start(1000)">Start</button>
                </div>
            </div>
        </div>
    </div>
        
    @endif
        

</div>
<div class="container mt-4" >
    
    <div class="row">
        <div class="col-4 text-center">
            <img src="{{ $item->gambar }}" width="75%" alt="" class="rounded-lg" style="border: 2px solid white">
        </div>
        <div class="col-8">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-footer text-bold" style="border-bottom: 1px solid rgb(192, 192, 192)">
                            <h4 class="my-0 text-bold float-left">
                                {{$item->namaregu." ".$item->ket}}
                            </h4>
                            <h4 class="my-0 text-bold float-right">
                                [{{$item->urutan}}]
                            </h4>
                        </div>
                        <div class="card-body text-lg p-0">
                            <table class="table table-striped">
                                <tr>
                                    <td width="30%">Name</td>
                                    <td width="10px">:</td>
                                    <td class="text-bold">
                                        @if ($item->waktu == true)
                                            {{ucwords($item->namagroup)}}
                                        @else
                                            {{ucwords($item->namapeserta)}}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td width="30%">Kontingen</td>
                                    <td width="10px">:</td>
                                    <td class="text-bold">{{$item->kontingen}}</td>
                                </tr>

                                <tr>
                                    <td width="30%">Gender</td>
                                    <td width="10px">:</td>
                                    <td class="text-bold">
                                        @if ($item->idbagian == "l")
                                            Male
                                        @elseif($item->idbagian == "p")
                                            Female
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card text-lg text-center">
                        <div class="card-header bg-secondary my-0 py-0">
                            Rating Result
                        </div>
                        <div class="card-body bg-info my-0">
                            @livewire('hitung-live', [
                                'idpesertatanding' => $item->idpesertatanding,
                                'idtanding' => $item->idtanding,
                            ])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <div class="bawah w-100">
        <div class="row text-center">
            <div class="col-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modelId">
                    <i class="fa fa-arrow-alt-circle-right"> Peserta Selanjutnya</i>
                </button>
            </div>
        </div>

    </div>



</div>




<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title ">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                sure you want to continue?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>
    
</form>
@endforeach


@if ($jumlahdata == 0)

<div class="back">
    <a href="{{ url('/monitor', []) }}" class="btn btn-lg btn-danger">
        <i class="fa fa-circle"></i> Refresh
    </a>
</div>
    <div class="container">
        .<div class="jumbotron text-center">
            <h1 class="display-3 text-uppercase">waiting for the match</h1>
            <hr class="my-2">
            <p>More info</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="{{ url('/hasil', []) }}" role="button">Championship Results</a>
            </p>
        </div>
    </div>


@endif
@endsection





@section('myScript')
<script>
    function _timer(callback)
    {
        var time = 0;     //  The default time of the timer
        var mode = 1;     //    Mode: count up or count down
        var status = 0;    //    Status: timer is running or stoped
        var timer_id;    //    This is used by setInterval function
        
        // this will start the timer ex. start the timer with 1 second interval timer.start(1000) 
        this.start = function(interval)
        {
            interval = (typeof(interval) !== 'undefined') ? interval : 1000;
    
            if(status == 0)
            {
                status = 1;
                timer_id = setInterval(function()
                {
                    switch(mode)
                    {
                        default:
                        if(time)
                        {
                            time--;
                            generateTime();
                            if(typeof(callback) === 'function') callback(time);
                        }
                        break;
                        
                        case 1:
                        if(time < 86400)
                        {
                            time++;
                            generateTime();
                            if(typeof(callback) === 'function') callback(time);
                        }
                        break;
                    }
                }, interval);
            }
        }
        
        //  Same as the name, this will stop or pause the timer ex. timer.stop()
        this.stop =  function()
        {
            if(status == 1)
            {
                status = 0;
                clearInterval(timer_id);
            }
        }
        
        // Reset the timer to zero or reset it to your own custom time ex. reset to zero second timer.reset(0)
        this.reset =  function(sec)
        {
            sec = (typeof(sec) !== 'undefined') ? sec : 0;
            time = sec;
            generateTime(time);
        }
        
        // Change the mode of the timer, count-up (1) or countdown (0)
        this.mode = function(tmode)
        {
            mode = tmode;
        }
        
        // This methode return the current value of the timer
        this.getTime = function()
        {
            return time;
        }
        
        // This methode return the current mode of the timer count-up (1) or countdown (0)
        this.getMode = function()
        {
            return mode;
        }
        
        // This methode return the status of the timer running (1) or stoped (1)
        this.getStatus
        {
            return status;
        }
        
        // This methode will render the time variable to hour:minute:second format
        function generateTime()
        {
            var second = time % 60;
            var minute = Math.floor(time / 60) % 60;
            var hour = Math.floor(time / 3600) % 60;
            
            second = (second < 10) ? '0'+second : second;
            minute = (minute < 10) ? '0'+minute : minute;
            hour = (hour < 10) ? '0'+hour : hour;
            
            document.getElementById('waktu').value=minute+":"+second;
            // $('div.timer span.second').html(second);
            // $('div.timer span.minute').html(minute);
            // $('div.timer span.hour').html(hour);
        }
    }
    
    // example use
    var timer;
    
    $(document).ready(function(e) 
    {
        timer = new _timer
        (
            function(time)
            {
                if(time == 0)
                {
                    timer.stop();
                }
            }
        );
        timer.reset(300);
        timer.mode(0);
    });
</script>
@endsection