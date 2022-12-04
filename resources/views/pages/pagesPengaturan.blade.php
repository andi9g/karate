@extends('layout.master')

@section('title')
    Pengaturan
@endsection

@section('activepengaturan')
    activeku
@endsection

@section('content')
    
<div class="container">
    <div class="card">
        <div class="card-header">
            PENGATURAN
        </div>

        <form action="{{ route('pengaturan.store', []) }}" method="post">
            @csrf
            <div class="card-body">
                <div class='form-group'>
                    <label for='forjumlahjuri' class='text-capitalize'>Jumlah Juri</label>
                    <input type='number' name='jumlahjuri' id='forjumlahjuri' class='form-control' placeholder='jumlah juri' value="{{empty($data->jumlahjuri)?'':$data->jumlahjuri}}">
                </div>
    
                <div class='form-group'>
                    <label for='forpendaftaran' class='text-capitalize'>Pendaftaran</label>
                    <select name='pendaftaran' id='forpendaftaran' class='form-control'>
                        <option value='0' @if ((empty($data->pendaftaran)?false:$data->pendaftaran)==false)
                            selected
                        @endif>Ditutup</option>
                        <option value='1'  @if ((empty($data->pendaftaran)?false:$data->pendaftaran)==true)
                            selected
                        @endif>Dibuka</option>
                    <select>
                </div>
            </div>
    
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </div>
        </form>
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