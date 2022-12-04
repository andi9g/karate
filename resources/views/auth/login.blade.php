@extends('layout.auth')

@section('title')
    Login
@endsection

@section('content')
<div class="register-logo">
    <a href=""><b>LOGIN</b></a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg font-italic">please enter your account</p>

      <form action="{{ route('proses.login', []) }}" method="post">
        @csrf

        <div class="input-group mb-3">
          <input type="text" class="form-control @error('username')
              is-invalid
          @enderror" name="username" placeholder="username" value="{{ old('username') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>

          @error('username')
              <div class="invalid-feedback">{{$message}}</div>
          @enderror
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control @error('password')
              is-invalid
          @enderror" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          @error('password')
              <div class="invalid-feedback">{{$message}}</div>
          @enderror
        </div>

        

        <div class="row">
          <div class="col-8">
            <div class='form-group'>
                <select name='posisi' id='forposisi' class='form-control'>
                    <option value='juri'>JURI</option>
                    <option value='admin'>ADMIN</option>
                    <option value='superadmin'>SUPERADMIN</option>
                <select>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block"> Sign In</button>
          </div>
          
        </div>
      </form>
      
    </div>
  </div>
@endsection