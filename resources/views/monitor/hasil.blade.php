@extends('layout.monitor')

@section('title')
    Ranking
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card text-center rounded-0 ">
                <div class="card-header rounded-0 bg-secondary">
                    <h3 class="text-uppercase my-0 text-bold">
                        {{$namakelas}}
                    </h3>
                </div>
                <div class="card-footer py-1 rounded-0">
                    <h4 class="text-uppercase my-0 py-0">
                        {{ $namabagian }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="back">
            <a href="{{ url('/monitor', []) }}" class="btn btn-lg btn-danger">
                <i class="fa fa-sign-out"></i> Back To Monitor
            </a>
    </div>
    
</div>

<div class="container">
    <div class="row">
        <div class="col text-center">
            <div class="card">
                <div class="card-header bg-info">
                    <h2 class="my-0">RANKING</h2>

                </div>

                <div class="card-body">
                    <table class="table table-striped table-hover text-lg">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Image</th>
                                <th class="text-left">Name</th>
                                <th>Kontingen</th>
                            </tr>

                        </thead>

                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-center" width="10px">
                                        <h5>
                                            {{($item->urutan==4)?3:$item->urutan}}
                                        </h5>
                                    </td>
                                    <td>
                                        <img src="{{$item->gambar}}" width="50px" class="rounded-lg" alt="">
                                    </td>
                                    <td class="text-left">
                                        <h5>{{$item->namapeserta}}</h5>
                                    </td>
                                    <td>
                                        <h5>{{$item->kontingen}}</h5>
                                    </td>
                                </tr>
                                
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



@section('myScript')
@endsection