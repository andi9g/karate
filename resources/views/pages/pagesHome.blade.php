@extends('layout.master')

@section('title')
    Lengkapi Identitas
@endsection

@section('activekuhome')
    activeku
@endsection

@section('content')
   <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-clock"></i></span>

                <div class="info-box-content">
                    <b>PENDAFATARAN</b>
                    <span class="info-box-text">Belum Terferifikasi</span>
                    <span class="info-box-number">
                        <h4 class="text-bold">
                            {{$belumterferifikasi}}
                        </h4>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                <div class="info-box-content">
                    <b>PENDAFATARAN</b>
                    <span class="info-box-text">Telah Terferifikasi</span>
                    <span class="info-box-number">
                        <h4 class="text-bold">
                            {{$terferifikasi}}
                        </h4>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
                <!-- /.info-box -->
        </div>
   </div>

   <div class="row">
    <div class="col-md-12">
        <div class="card card-default">
          <div class="card-header bg-info">
            <h3 class="card-title">
              <i class="fas fa-bullhorn"></i>
              Informasi Penting
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="">
            @foreach ($lomba as $item)
            <div class="callout callout-info border-info disabled" style="box-shadow: 1px 1px 5px rgb(61, 149, 172)">
              <h5 class="text-bold">{{$item->namalomba}}</h5>

              <ul>
                <li><b>PROPOSAL</b> dapat dilihat pada link berikut 
                    <a href="{{$item->proposal}}" target="_blank" class="text-primary" style="font-size: 15pt">Klik!</a>
                </li>
                <li>
                    Tanggal Pertandingan <b>{{$item->tanggallomba}}</b>
                </li>
                <li>
                    Penutupan Pendaftaran <b>{{\Carbon\Carbon::parse($item->tanggaltutup)->isoFormat('dddd, DD MMMM YYYY')}}</b>
                </li>
                <li>
                    Pengumpulan berkas maksimal <b>{{\Carbon\Carbon::parse($item->tanggalberkas)->isoFormat('dddd, DD MMMM YYYY')}}</b>
                </li>
                <li>
                    <b>KELAS PERTANDINGAN</b>
                    <ul>
                        @foreach ($kelas as $item)
                        <li>{{$item->namakelas}}</li>
                            
                        @endforeach
                    </ul>
                </li>
              </ul>
            </div>
                
            @endforeach
            
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
   </div>


@endsection


@section('script')
@endsection