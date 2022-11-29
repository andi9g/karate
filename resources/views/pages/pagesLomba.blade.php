@extends('layout.master')

@section('title')
    Perlombaan
@endsection

@section('activekuLomba')
    activeku
@endsection

@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahlomba">
                Tambah Perlombaan
            </button>
            
            <div class="modal fade" id="tambahlomba" tabindex="-1" aria-labelledby="tambahlombaLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="tambahlombaLabel">Tambah Perlombaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('lomba.store', []) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class='form-group'>
                                <label for='fornamalomba' class='text-capitalize'>Nama Lomba</label>
                                <input type='text' name='namalomba' id='fornamalomba' class='form-control' placeholder='masukan nama lomba'>
                            </div>
    
                            <div class='form-group'>
                                <label for='forproposal' class='text-capitalize'>Link Proposal Lomba</label>
                                <input type='text' name='proposal' id='forproposal' class='form-control' placeholder="Link proposal (Google Drive)">
                            </div>
    
                            <div class='form-group'>
                                <label for='fortanggalberkas' class='text-capitalize'> Batas Pengumpulan Berkas</label>
                                <input type='datetime-local' name='tanggalberkas' id='fortanggalberkas' class='form-control' placeholder='masukan namaplaceholder'>
                            </div>
    
                            <div class='form-group'>
                                <label for='fortanggallomba' class='text-capitalize'>Tanggal Lomba</label>
                                <input type='text' name='tanggallomba' id='fortanggallomba' class='form-control' placeholder='Contoh : 8-9 Desember 2022'>
                            </div>
    
                            <div class='form-group'>
                                <label for='fortanggaltutup' class='text-capitalize'>Batas Pendaftaran</label>
                                <input type='datetime-local' name='tanggaltutup' id='fortanggaltutup' class='form-control' placeholder='masukan namaplaceholder'>
                            </div>
    
                            <div class='form-group'>
                                <label for='forwa1' class='text-capitalize'>Whatsapp 1</label>
                                <input type='text' name='wa1' id='forwa1' class='form-control' placeholder='masukan nomor Whatsapp'>
                            </div>
                            <div class='form-group'>
                                <label for='forwa2' class='text-capitalize'>Whatsapp 2</label>
                                <input type='text' name='wa2' id='forwa2' class='form-control' placeholder='masukan nomor Whatsapp'>
                            </div>
                            <div class='form-group'>
                                <label for='fortahun' class='text-capitalize'>Tahun Lomba</label>
                                <input type='number' name='tahun' id='fortahun' class='form-control' value="{{date('Y')}}">
                            </div>
                            <script>
                                function pilih(pilihan) {
                                    var pilih = pilihan.value;
                                    if(pilih == "intern") {
                                        document.getElementById('forintern').hidden=false;
                                    }else{
                                        document.getElementById('forintern').hidden=true;
    
                                    }
                                }
                            </script>
                            <div class='form-group'>
                                <label for='forakses' class='text-capitalize'>Perlombaan</label>
                                <select name='akses' id='forakses' onchange="pilih(this)" class='form-control'>
                                    <option value='open'>Kejuaraan Umum (Open)</option>
                                    <option value='intern'>Kejuaraan Intern</option>
                                <select>
                                <input type='text' hidden name='intern' id='forintern' class='form-control rounded-0' placeholder='masukan nama intern'>
                            </div>
    
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Perlombaan</button>
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



    <div class="row">
        <div class="col-md-12 table-responsive">
            {{empty($message)?"":$message}}
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lomba</th>
                        <th>Tanggal Lomba</th>
                        <th>Tahun</th>
                        <th>Kejuaraan</th>
                        <th>Aksi</th>
                        <th>Status</th>
                        <th>Proses</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lomba as $item)
                        <tr>
                            <td>{{$loop->iteration + $lomba->firstItem() - 1}}</td>
                            <td class="text-bold">{{ucwords($item->namalomba)}}</td>
                            <td>{{$item->tanggallomba}}</td>
                            <td>{{$item->tahun}}</td>
                            <td class="text-bold">
                                @php
                                    if ($item->akses == "") {
                                        echo "OPEN";
                                    }else {
                                        echo "INTERN ".strtoupper($item->akses);
                                    }
                                    
                                @endphp
                            </td>

                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-info badge-btn border-0 d-inline" data-toggle="modal" data-target="#detail{{$item->idlomba}}">
                                  <i class="fa fa-eye">Detail</i>
                                </button>
                                
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-primary badge-btn border-0 d-inline" data-toggle="modal" data-target="#edit{{$item->idlomba}}">
                                  <i class="fa fa-edit"></i> Edit
                                </button>
                                
                                <form action="{{ route('lomba.destroy', [$item->idlomba]) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge badge-danger badge-btn border-0" onclick="return confirm('Lanjutkan proses hapus?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                                
                                
                            </td>

                            <td>
                                @if ($item->ket == true)
                                    <div class="badge badge-success">Telah Dibuka</div>
                                @else
                                    <div class="badge badge-danger">Telah Selesai</div>
                                @endif
                            </td>

                            <td>
                                @if ($item->ket == true)
                                    <form action="{{ route('lomba.proses', [$item->idlomba]) }}" method="post">
                                        @csrf
                                        <!-- Button trigger modal -->
                                        <button type="button" class="badge badge-warning border-0 badge-btn text-bold" data-toggle="modal" data-target="#proses{{$item->idlomba}}">
                                          SELESAIKAN
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="proses{{$item->idlomba}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title"><i class="fa-exclamation-triangle"></i>Lanjutkan Proses?</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Jika menekan <b>YA</b> maka Perlombaan akan di akhiri
                                                    </div>
                                                    <div class="modal-footer bg-danger">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <button type="submit" class="btn btn-primary">Ya</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                @else

                                <form action="{{ route('lomba.proses', [$item->idlomba]) }}" method="post">
                                    @csrf
                                    <!-- Button trigger modal -->
                                    <button type="button" class="badge badge-secondary border-0 badge-btn text-bold" data-toggle="modal" data-target="#proses{{$item->idlomba}}">
                                      BUKA
                                    </button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="proses{{$item->idlomba}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-secondary">
                                                    <h5 class="modal-title"><i class="fa-exclamation-triangle"></i>Lanjutkan Proses?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                                <div class="modal-body">
                                                    Jika menekan <b>YA</b> maka Perlombaan akan di Buka
                                                </div>
                                                <div class="modal-footer bg-secondary">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                    <button type="submit" class="btn btn-primary">Ya</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="edit{{$item->idlomba}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Form Edit</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>
                                    <form action="{{ route('lomba.update', [$item->idlomba]) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class='form-group'>
                                                <label for='fornamalomba' class='text-capitalize'>Nama Lomba</label>
                                                <input type='text' name='namalomba' id='fornamalomba' class='form-control' placeholder='masukan nama lomba' value="{{$item->namalomba}}">
                                            </div>
                                            <div class='form-group'>
                                                <label for='forproposal' class='text-capitalize'>Link Proposal Pertandingan</label>
                                                <input type='text' name='proposal' id='forproposal' class='form-control' placeholder='masukan nama lomba' value="{{$item->proposal}}">
                                            </div>
                    
                    
                                            <div class='form-group'>
                                                <label for='fortanggalberkas' class='text-capitalize'> Batas Pengumpulan Berkas</label>
                                                <input type='datetime-local' name='tanggalberkas' id='fortanggalberkas' class='form-control' placeholder='masukan namaplaceholder' value="{{$item->tanggalberkas}}">
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='fortanggallomba' class='text-capitalize'>Tanggal Lomba</label>
                                                <input type='text' name='tanggallomba' id='fortanggallomba' class='form-control' placeholder='Contoh : 8-9 Desember 2022' value="{{$item->tanggallomba}}">
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='fortanggaltutup' class='text-capitalize'>Batas Pendaftaran</label>
                                                <input type='datetime-local' name='tanggaltutup' id='fortanggaltutup' class='form-control' placeholder='masukan namaplaceholder' value="{{$item->tanggaltutup}}">
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='forwa1' class='text-capitalize'>Whatsapp 1</label>
                                                <input type='text' name='wa1' id='forwa1' class='form-control' placeholder='masukan nomor Whatsapp' value="{{$item->wa1}}">
                                            </div>
                                            <div class='form-group'>
                                                <label for='forwa2' class='text-capitalize'>Whatsapp 2</label>
                                                <input type='text' name='wa2' id='forwa2' class='form-control' placeholder='masukan nomor Whatsapp' value="{{$item->wa2}}">
                                            </div>
                                            <div class='form-group'>
                                                <label for='fortahun' class='text-capitalize'>Tahun Lomba</label>
                                                <input type='number' name='tahun' id='fortahun' class='form-control' value="{{$item->tahun}}">
                                            </div>
                                            <script>
                                                function pilih{{$item->idlomba}}(pilihan) {
                                                    var pilih = pilihan.value;
                                                    if(pilih == "intern") {
                                                        document.getElementById('forintern{{$item->idlomba}}').hidden=false;
                                                    }else{
                                                        document.getElementById('forintern{{$item->idlomba}}').hidden=true;
                    
                                                    }
                                                }
                                            </script>
                                            <div class='form-group'>
                                                <label for='forakses' class='text-capitalize'>Perlombaan</label>
                                                <select name='akses' id='forakses' onchange="pilih{{$item->idlomba}}(this)" class='form-control'>
                                                    <option value='open' @if ($item->akses == null)
                                                        selected
                                                    @endif>Kejuaraan Umum (Open)</option>
                                                    <option value='intern' @if (!empty($item->akses))
                                                        selected
                                                    @endif>Kejuaraan Intern</option>
                                                <select>
                                                <input type='text' @if (empty($item->akses))
                                                    hidden
                                                @endif name='intern' id='forintern{{$item->idlomba}}' class='form-control rounded-0 text-uppercase' placeholder='masukan nama intern' value="{{strtoupper($item->akses)}}">
                                            </div>

                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Ubah Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="detail{{$item->idlomba}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>
                                    <div class="modal-body">
                                            <div class='form-group'>
                                                <label for='fornamalomba' class='text-capitalize'>Nama Lomba</label>
                                                <input type='text' name='namalomba' id='fornamalomba' class='form-control' disabled value='{{ucwords($item->namalomba)}}'>
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for="">Proposal Lomba</label>
                                                <h5>
                                                    <a href="{{ $item->proposal }}" target="_blank" class="badge badge-info">Lihat Proposal Lomba</a>

                                                </h5>
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='fortanggalberkas' class='text-capitalize'> Batas Pengumpulan Berkas</label>
                                                <input type='datetime-local' name='tanggalberkas' id='fortanggalberkas' class='form-control' disabled value="{{$item->tanggalberkas}}">
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='fortanggallomba' class='text-capitalize'>Tanggal Lomba</label>
                                                <input type='text' name='tanggallomba' id='fortanggallomba' class='form-control' disabled placeholder='Contoh : 8-9 Desember 2022' value="{{$item->tanggallomba}}">
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='fortanggaltutup' class='text-capitalize'>Batas Pendaftaran</label>
                                                <input type='datetime-local' name='tanggaltutup' id='fortanggaltutup' class='form-control' disabled placeholder='masukan namaplaceholder' value="{{$item->tanggaltutup}}">
                                            </div>
                    
                                            <div class='form-group'>
                                                <label for='forwa1' class='text-capitalize'>Whatsapp 1</label>
                                                <input type='text' name='wa1' id='forwa1' class='form-control' disabled placeholder='masukan nomor Whatsapp' value="{{$item->wa1}}">
                                            </div>
                                            <div class='form-group'>
                                                <label for='forwa2' class='text-capitalize'>Whatsapp 2</label>
                                                <input type='text' name='wa2' id='forwa2' class='form-control' disabled placeholder='masukan nomor Whatsapp' value="{{$item->wa2}}">
                                            </div>
                                            <div class='form-group'>
                                                <label for='fortahun' class='text-capitalize'>Tahun Lomba</label>
                                                <input type='number' name='tahun' id='fortahun' class='form-control' disabled value="{{$item->tahun}}">
                                            </div>
                    
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            {{$lomba->links('vendor.pagination.bootstrap-4')}}
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