<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('components/bootstrap/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">IDMS</a>
            </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    @yield('nav')
                </ul>
            </div>
        </nav>
        @yield('content')
    <script src="{{ asset(('components/jquery/jquery.min.js'))}}"></script>
    <script src="{{ asset('components/bootstrap/js/bootstrap.min.js')}}"></script>
    </div>
</body>
</html>
