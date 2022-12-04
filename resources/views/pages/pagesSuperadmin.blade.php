@extends('layout.master')

@section('title')
    Data Superadmin
@endsection

@section('activekusuperadmin')
    activeku
@endsection

@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahadmin">
                Tambah Superadmin
            </button>
            
            <div class="modal fade" id="tambahadmin" tabindex="-1" aria-labelledby="tambahadminLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="tambahadminLabel">Tambah Superadmin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('superadmin.store', []) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class='form-group'>
                                <label for='forusername' class='text-capitalize'>Username</label>
                                <input type='text' name='username' id='forusername' class='form-control' placeholder='masukan username'>
                            </div>

                            <div class='form-group'>
                                <label for='fornamasuperadmin' class='text-capitalize'>Nama Pengguna</label>
                                <input type='text' name='namasuperadmin' id='forusername' class='form-control' placeholder='masukan namasuperadmin'>
                            </div>
                            <div class='form-group'>
                                <label for='forpassword' class='text-capitalize'>password</label>
                                <input type='password' name='password' id='forusername' class='form-control' placeholder='masukan password'>
                            </div>

                            
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Superadmin</button>
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
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($superadmin as $item)
                        <tr>
                            <td width="5px">{{$loop->iteration}}</td>
                            <td class="text-bold">{{ucwords($item->namasuperadmin)}}</td>
                            <td>{{$item->username}}</td>
                            <td>
                                <form action="{{ route('superadmin.destroy', [$item->idsuperadmin]) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge badge-danger badge-btn border-0" onclick="return confirm('yakin ingin di hapus?')">
                                        <i class="fa fa-trash"></i> hapus
                                    </button>
                                </form>
                                
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-primary badge-btn border-0" data-toggle="modal" data-target="#edit{{$item->idsuperadmin}}">
                                  <i class="fa fa-edit"></i> Edit
                                </button>
                                
                        
                            </td>
                            
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="edit{{$item->idsuperadmin}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <h5 class="modal-title">Form Edit</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                            </div>
                                    <form action="{{ route('superadmin.update', [$item->idsuperadmin]) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                    
                                        <div class="modal-body">
                
                                            <div class='form-group'>
                                                <label for='fornamasuperadmin' class='text-capitalize'>Nama Pengguna</label>
                                                <input type='text' name='namasuperadmin' id='forusername' class='form-control' placeholder='masukan namasuperadmin' value="{{$item->namasuperadmin}}">
                                            </div>
                                            <div class='form-group'>
                                                <label for='forpassword' class='text-capitalize'>password</label>
                                                <input type='password' name='password' id='forusername' class='form-control' placeholder='masukan password'>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Edit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            {{-- {{$superadmin->links('vendor.pagination.bootstrap-4')}} --}}
        </div>
    </div>


</div>

@foreach ($superadmin as $item)
    
@endforeach

@endsection


@section('script')
<script>
    $(".js-example-tags").select2({
      tags: true
    });

    
</script>
@endsection