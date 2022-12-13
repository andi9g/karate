@extends('layout.master')

@section('title')
    Pendaftaran
@endsection

@section('activekupendaftaran')
    activeku
@endsection

@section('content')
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-lg btn-block text-bold" data-toggle="modal" data-target="#pertandingan">
                  <i class="fa fa-plus"></i> MENDAFTAR PERTANDINGAN
                </button>
                
                <!-- Modal -->
                <div class="modal fade" id="pertandingan" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title">Form Pendaftaran Pertandingan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>

                            <form action="{{ route('pendaftaran.store', []) }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class='form-group'>
                                            <label for='foridkelas' class='text-capitalize'>Kelas Pertandingan</label>
                                            <select name='idkelas' id="pendaftaran" class='form-control' style="width: 100%" required>
                                                <option value=''>Pilih Kelas Pertandingan</option>
                                                @foreach ($kelas as $item)
                                                    <option value="{{$item->idkelas}}">{{$item->namakelas}}</option>
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
                                            <label for='fornamabagian' class='text-capitalize'>Kategori</label>
                                            <input type='text' disabled id='fornamabagian' class='form-control text-capitalize' value="{{$namabagian}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success px-5 text-bold">MENDAFTAR</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        @if ($jumlahdaftar > 0)
        {{-- @foreach ($lomba as $item) --}}
        <div class="alert alert-warning alert-dismissible mt-2">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Pengumpulan Berkas</h5>
            Silahkan mengumpulkan berkas Absah paling lambat <b>{{\Carbon\Carbon::parse($lomba->tanggalberkas)->isoFormat('dddd, DD MMMM YYYY')}}</b>, meliputi :
            <ul class="mb-0 pb-1">
                <li>Fotocopy Akta Kelahiran</li>
                <li>Surat Keterangan Sehat</li>
                <li>Bukti Pembayaran</li>
            </ul>
            Pengiriman berkas bisa melalui WA <b>{{
                empty($item->wa1)?"Tidak Tersedia": $item->wa1; 
            }}
            </b>{{
                empty($item->wa2)?"": "atau ";
            }}<b>
            {{
                empty($item->wa2)?"": "" . $item->wa2;
            }}
            </b><br>
            Penutupan pendaftaran <b>{{
                empty($item->tanggaltutup)?"Belum dipastikan":\Carbon\Carbon::parse($item->tanggaltutup)->isoFormat('dddd, DD MMMM YYYY')
            }}
            </b>
            
          </div>
            
        {{-- @endforeach --}}
        @endif

        <div class="row mt-3">
            @foreach ($pertandingan as $item)
                
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header p-1 bg-secondary"></div>
                    <div class="card-body">
                        <label for="" class="text-uppercase my-0 py-0">Kelas Pertandingan :</label>
                        <ul>
                            <li>
                                <h6 class="my-0 py-0 text-capitalize">{{$item->namakelas}}</h6>
                            </li>
                            <li>
                                <h6 class="my-0 py-0 text-capitalize">{{$item->namabagian}}</h6>
                            </li>
                            <li>
                                <h6 class="my-0 py-0 text-capitalize">{{$item->namalomba}}</h6>
                            </li>
                            <li>
                                <h6 class="my-0 py-0 text-capitalize">Tanggal lomba : {{$item->tanggallomba}}</h6>
                            </li>
                            <li>
                                <h6 class="my-0 py-0 text-capitalize">{{$item->kontingen}}</h6>
                            </li>
                        </ul>

                        <label for="" class="my-0 py-0 text-uppercase">Telah Terdaftar Pada :</label>
                        <p class="my-0 py-0 text-success"> 
                            {{\Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, DD MMMM YYYY')}}
                        </p>
                        
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <form action="{{ route('pendaftaran.destroy', [$item->idpertandingan]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Lanjutkan proses menghapus?')">
                                        <i class="fa fa-trash"></i> Hapus Pendaftaran
                                    </button>
                                </form>
                            </div>
                            <div class="col-6">
                                @if ($item->sah == true)
                                <button disabled class="btn btn-success text-bold btn-block">
                                    <i class="fa fa-check"></i> Telah Terferifikasi
                                </button>
                                @else
                                <button disabled class="btn btn-secondary btn-block">
                                    <i class="fa fa-ban"></i> Belum Terferifikasi
                                </button>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>


    </div>

@endsection


@section('script')
<script>
    $("#pendaftaran").select2();

    
</script>
@endsection