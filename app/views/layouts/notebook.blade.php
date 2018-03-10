<!DOCTYPE html>
<html lang="en" class="bg-sc-grey">
<head>
  <meta charset="utf-8" />
  <title>@yield('title')</title>
  <meta name="description" content="IDMS | Information and Data Management System" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="{{ asset('components/bootstrap/css/bootstrap.min.css') }}">

  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('css/font.css') }}" type="text/css" cache="false" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css" />
  <!--[if lt IE 9]>
    <script src="{{ asset('js/ie/html5shiv.js') }}" cache="false"></script>
    <script src="{{ asset('js/ie/respond.min.js') }}" cache="false"></script>
    <script src="{{ asset('js/ie/excanvas.js') }}" cache="false"></script>
  <![endif]-->
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    @yield('content')
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder">
      <p>
        <small>Information and Data Management System <br>
        Invast &copy; {{ date("Y") }}</small>
      </p>
    </div>
  </footer>
  <!-- / footer -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('js/bootstrap.js') }}"></script>
  <!-- App -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/app.plugin.js') }}"></script>
  <script src="{{ asset('js/slimscroll/jquery.slimscroll.min.js') }}"></script>

</body>
</html>
