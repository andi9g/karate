@extends('layout.master')

@section('title')
    Perlombaan
@endsection

@section('activekulapangan')
    activeku
@endsection

@section('judul')
    <h3>Data Lapangan</h3>
@endsection
@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahlapangan">
                Tambah Perlombaan
            </button>
            
            <div class="modal fade" id="tambahlapangan" tabindex="-1" aria-labelledby="tambahlapanganLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="tambahlapanganLabel">Tambah Lapangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('lapangan.store', []) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class='form-group'>
                                <label for='fornamalapangan' class='text-capitalize'>Nama Lapangan</label>
                                <input type='text' name='namalapangan' id='fornamalapangan' class='form-control' placeholder='masukan nama lomba'>
                            </div>
    
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Lapangan</button>
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
                        <th>Nama Lapangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lapangan as $item)
                        <tr>
                            <td width="5px">{{$loop->iteration + $lapangan->firstItem() - 1}}</td>
                            <td class="text-bold">{{ucwords($item->namalapangan)}}</td>
                            <td>
                                <form action='{{ route('lapangan.destroy', [$item->idlapangan]) }}' method='post' class='d-inline'>
                                     @csrf
                                     @method('DELETE')
                                     <button type='submit' onclick='return confirm("lanjutkan proses hapus?")' class='badge badge-danger badge-btn border-0'>
                                         <i class="fa fa-trash"></i> Hapus
                                     </button>
                                </form>

                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-primary badge-btn border-0" data-toggle="modal" data-target="#edit{{$item->idlapangan}}">
                                  <i class="fa fa-edit"></i> Edit
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{$item->idlapangan}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Form Edit</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                            </div>
                                            <form action="{{ route('lapangan.update', [$item->idlapangan]) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class='form-group'>
                                                        <label for='fornamalapangan' class='text-capitalize'>Nama Lapangan</label>
                                                        <input type='text' name='namalapangan' id='fornamalapangan' class='form-control' placeholder='masukan namaplaceholder' value="{{$item->namalapangan}}">
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
                            </td>
                        </tr>

                        
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            {{$lapangan->links('vendor.pagination.bootstrap-4')}}
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