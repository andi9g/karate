@extends('layout.master')

@section('title')
    Daftar
@endsection

@section('activekudaftar')
    activeku
@endsection

@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahdaftar">
                Tambah Peserta
            </button>
            
            <div class="modal fade" id="tambahdaftar" tabindex="-1" aria-labelledby="tambahdaftarLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="tambahdaftarLabel">FORM DAFTAR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('daftar.store', []) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class='form-group'>
                                <label for='fornamapeserta' class='text-capitalize'>Nama Peserta</label>
                                <input type='text' name='namapeserta' id='fornamapeserta' class='form-control' placeholder='masukan namapeserta'>
                            </div>

                            <div class='form-group'>
                                <label for='fornama'  class='text-capitalize '>Nama Kontingen</label>
                                <small>(Contingent Name)</small>
                                <select class="form-control js-example-tags @error('kontingen')
                                    is-invalid
                                @enderror" style="width: 100%" name="kontingen" required>
                                <option value="">Silahkan Ketikan Kontingen</option>
                                    @foreach ($kontingen as $item)
                                        <option value="{{$item->kontingen}}">{{$item->kontingen}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class='form-group'>
                                <label for='fornama' class='text-capitalize'>Jenis Kelamin</label>
                                <small>(Gender)</small>
                                <div class="form-check">
                                    <input class="form-check-input @error('jk')
                                        is-invalid
                                    @enderror" type="radio" name="jk" id="jk1" value="l">
                                    <label class="form-check-label" for="jk1">
                                      Laki-Laki <small>(Malee)</small>
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input @error('jk')
                                        is-invalid
                                    @enderror" type="radio" name="jk" id="jk2" value="p">
                                    <label class="form-check-label" for="jk2">
                                      Perempuan <small>(Femalee)</small>
                                    </label>
                                  </div>
                            </div>

                            <div class='form-group'>
                                <label for='forkelaspertandingan' class='text-capitalize'>Kelas Pertandingan</label>
                                <select name='kelaspertandingan' id='forkelaspertandingan' class='form-control'>
                                    <option value=''>Pilih</option>
                                    @foreach ($kelas as $k)
                                        <option value='{{$k->idkelas}}'>{{$k->namakelas}}</option>
                                    @endforeach
                                <select>
                            </div>

                            <div class='form-group'>
                                <label for='forlomba' class='text-capitalize'>Jenis Lomba</label>
                                <select name='lomba' id='forlomba' class='form-control'>
                                    <option value='' disabled selected>Pilih Perlombaan</option>
                                    @foreach ($perlombaan as $item)
                                        <option value='{{$item->idlomba}}'>{{$item->namalomba}} ({{$item->tanggallomba}})</option>
                                    @endforeach
                                <select>
                            </div>

                            <div class='form-group'>
                                <label for='forgambar' class='text-capitalize'>Fas Photo - Latar Merah - Seragam Karate</label>
                                <small>(Photo Shoot - Red Backgroud - karate uniform)</small>
                                
                                <input type='file' name='gambar' id='forgambar' class="form-control @error('gambar')
                                    is-invalid
                                @enderror" accept="image/*" value="{{old('gambar')}}">
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah daftar</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <form action="{{ url()->current() }}" class="form-inline justify-content-end">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" aria-describedby="button-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Kontingen</th>
                            <th>Kelamin</th>
                            <th>email</th>
                            <th>password</th>
                            <th>aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($peserta as $item)
                            <tr>
                                <td>{{$loop->iteration + $peserta->firstItem() - 1}}</td>
                                <td>{{$item->namapeserta}}</td>
                                <td>{{$item->kontingen}}</td>
                                <td>{{($item->jk=='l')?'Laki-laki':'Perempuan'}}</td>
                                <td>{{$item->email}}</td>
                                <td>
                                    @php
                                        $pass = $item->password;
                                    @endphp
                                    @if (Hash::check('newpassword', $pass))
                                        newpassword
                                    @else
                                        -
                                    @endif

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
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