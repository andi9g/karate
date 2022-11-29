
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  @include('layout.header')
</head>
<body class="hold-transition register-page">

<div class="register-box">
  @yield('content')
</div>

@include('layout.script')

</body>
</html>
