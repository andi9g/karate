@extends('layout.master')

@section('title')
    Data Admin
@endsection

@section('activekuadmin')
    activeku
@endsection

@section('content')
    
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahadmin">
                Tambah Admin
            </button>
            
            <div class="modal fade" id="tambahadmin" tabindex="-1" aria-labelledby="tambahadminLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="tambahadminLabel">Tambah Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('admin.store', []) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class='form-group'>
                                <label for='forusername' class='text-capitalize'>Username</label>
                                <input type='text' name='username' id='forusername' class='form-control' placeholder='masukan username'>
                            </div>

                            <div class='form-group'>
                                <label for='forlapangan' class='text-capitalize'>Lapangan</label>
                                <select name='lapangan' id='forlapangan' class='form-control'>
                                    <option value=''>Pilih</option>
                                    @foreach ($lapangan as $l)
                                        <option value="{{$l->idlapangan}}">{{ucwords($l->namalapangan)}}</option>
                                    @endforeach
                                <select>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Admin</button>
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
                        <th>Username</th>
                        <th>Password</th>
                        <th>Lapangan</th>
                        <th>Juri</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($admin as $item)
                        <tr>
                            <td width="5px">{{$loop->iteration + $admin->firstItem() - 1}}</td>
                            <td class="text-bold">{{ucwords($item->username)}}</td>
                            <td>{{$item->password2}}</td>
                            <td class="text-bold">
                                {{$item->namalapangan}}
                            </td>
                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="badge badge-primary badge-btn border-0 w-100" data-toggle="modal" data-target="#juri{{$item->idadmin}}">
                                  <i class="fa fa-users"></i> Juri
                                </button>
                                
                                
                            </td>
                            <td>
                                <form action="{{ route('admin.destroy', [$item->idadmin]) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge badge-danger badge-btn border-0" onclick="return confirm('yakin ingin di hapus?')">
                                        <i class="fa fa-trash"></i> hapus
                                    </button>
                                </form>
                                
                                <form action="{{ route('reset.admin', [$item->idadmin]) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="badge badge-primary badge-btn border-0" onclick="return confirm('yakin ingin direset?')">
                                        <i class="fa fa-key"></i> Reset
                                    </button>
                                </form>

                                <a href="{{ route('admin.cetak', [$item->idadmin]) }}" class="badge badge-secondary badge-btn border-0" target="_blank">
                                    <i class="fa fa-print"></i> Cetak
                                </a>
                            </td>
                            
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            {{$admin->links('vendor.pagination.bootstrap-4')}}
        </div>
    </div>


</div>

@foreach ($admin as $item)
    <!-- Modal -->
    <div class="modal fade" id="juri{{$item->idadmin}}" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">Data Juri</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        @php
                            $juri = DB::table('juri')->where('idadmin', $item->idadmin)->orderBy('posisi', 'asc')->get();
                        @endphp

                        <table class="table table-bordered table-sm table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">password</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($juri as $j)
                            <tr>
                                <td class="text-bold text-center">{{$j->username}}</td>
                                <td class="text-bold text-center">{{$j->password2}}</td>
                            </tr>
                                
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection


@section('script')
<script>
    $(".js-example-tags").select2({
      tags: true
    });

    
</script>
@endsection