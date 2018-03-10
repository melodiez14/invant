<!DOCTYPE html>
<html lang="en" class="app">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | IDMS</title>
    <meta name="description" content="IDMS | Information and Data Management System" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/font.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('js/calendar/bootstrap_calendar.css') }}" type="text/css" cache="false" /> @yield('pagecss')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css" cache="false" />
    <!--[if lt IE 9]>
    <script src="{{ asset('js/ie/html5shiv.js') }}" cache="false"></script>
    <script src="{{ asset('js/ie/respond.min.js') }}" cache="false"></script>
    <script src="{{ asset('js/ie/excanvas.js') }}" cache="false"></script>
    <![endif]-->
    <style>
        .notification.dropdown-menu {
            width: 300px !important;
            max-height: 400px !important;
            overflow: auto;
        }

        .notif-text a {}

        .notif-text a {
            white-space: initial !important;
            padding-top: 15px !important;
            padding-bottom: 15px !important;
            word-wrap: break-word !important;
        }

        .notification-date {
            color: grey !important;
        }

        .locale.dropdown-menu {
            width: 100px !important;
            max-height: 400px !important;
            overflow: auto;
        }
    </style>
</head>

<body>
    {{-- Loading animation --}}
    <div class="loading"></div>

    {{-- BEGIN vbox section. TODO: do we need footer? vbox contain header,section, and footer --}}
    <section class="vbox">
        {{-- BEGIN Header --}}
        <header class="bg-danger stc header navbar navbar-fixed-top-xs">
            {{-- BEGIN Brand and toggle get grouped for better mobile display (centered) --}}
            <div class="navbar-header aside-md">
                {{-- Toggle button, only show on mobile --}}
                <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
                    <i class="fa fa-bars"></i>
                </a>
                {{-- Logo --}}
                <a href="#" class="navbar-brand" data-toggle="fullscreen">
                    <span class="fa fa-group icon m-r-sm"></span>IDMS</a>
                {{-- Profile, only show on mobile --}}
                <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
                    <i class="fa fa-cog"></i>
                </a>
            </div>
            {{-- END Brand and toggle --}} {{-- BEGIN Notification and Profile, grouped --}}
            <ul class="nav navbar-nav navbar-right hidden-xs nav-user">
                {{--Begin Locale--}}
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="language-toggle">
                        <i class="fa fa-flag"> </i>
                        <span class="badge bg-info"> {{ empty(Session::get('locale')) ? "ENGLISH" : (Session::get('locale') == 'en' ? 'ENGLISH' : 'INDONESIA'
                            )}} </span>
                        <b class="caret"></b>
                    </a>
                    {{-- BEGIN Locale Menu --}}
                    <ul class="locale dropdown-menu animated fadeInRight div-col-sm-12" style="padding: 0%">
                        <li>
                            <form action="{{ URL::route('language_chooser') }}" method="post">
                                <input name="locale" type="hidden" value="en">
                                <input type="submit" value="English" class="btn btn-default" style="width: 100%; border: transparent">
                            </form>
                        </li>
                        <li>
                            <form action="{{ URL::route('language_chooser') }}" method="post">
                                <input name="locale" type="hidden" value="id">
                                <input type="submit" value="Indonesia" class="btn btn-default" style="width: 100%; border: transparent">
                            </form>
                        </li>
                    </ul>
                </li>
                {{--
                <li>
                    <a href="http://localhost:8000/sentTestNotif">SEND</a>
                </li> --}} {{-- Profile --}}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{-- Profile Picture --}}
                        <span class="thumb-sm avatar pull-left">
                            {{-- If user has photo (staff) --}} @if (isset(Auth::user()->profile->photo_id))
                            <img src="{{ route('uploads.show', ['uploads' => Auth::user()->profile->photo_id]) }}"> @else
                            <img src="{{ asset('images/avatar_default.jpg')}}"> {{-- default pict if no picture --}} @endif
                        </span>
                        {{ (isset(Auth::user()->profile->name)) ? Auth::user()->profile->name : 'Anonymous' }}
                        <b class="caret"></b>
                    </a>
                    {{-- BEGIN Profile Menu --}}
                    <ul class="dropdown-menu animated fadeInRight">
                        {{-- arrow top above menu--}}
                        <span class="arrow top"></span>
                        {{-- Menu Item--}}
                        <li>
                            <a href="{{ route('users.edit', ['users' => Auth::user()->id]) }}">Update Account</a>
                        </li>
                        <li>
                            <?php
                                $upUrl = route('staffs.create') . '?user=' . Auth::user()->id;

                                if (isset(Auth::user()->profile->id)) $upUrl = route('staffs.edit', ['staffs' => Auth::user()->profile->id]);
                            ?>

                                <a href="{{ $upUrl }}">Update Profile</a>
                        </li>
                        {{-- Divider line --}}
                        <li class="divider"></li>
                        <li>
                            <a href="{{ URL::to('logout') }}">Logout</a>
                        </li>
                    </ul>
                </li>
                {{-- END Profile Menu --}}
            </ul>
            {{-- END Notification and Profile --}}

        </header>
        {{-- END Header --}} {{-- BEGIN content section (sidebar nav + content) --}}
        <section>
            {{-- BEGIN hbox (horizontal container). Contain: aside, content, aside --}}
            <section class="hbox stretch">

                {{-- BEGIN sidebar nav --}}
                <aside class="bg-light aside-md hidden-print" id="nav">
                    {{-- BEGIN vbox inside sidebar nav (to make header,content and footer) --}}
                    <section class="vbox">
                        {{-- BEGIN slimscroll sidebar nav content : Make sidebar nav scrollable with slimscroll --}}
                        <section class="w-f scrollable">
                            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-color="#333333">
                                {{-- BEGIN sidebar nav container --}}
                                <nav class="nav-primary hidden-xs">
                                    {{-- BEGIN nav item --}}
                                    <ul class="nav">
                                        {{ build_dashboard_nav() }}
                                    </ul>
                                    {{-- END nav item --}}
                                </nav>
                                {{-- END sidebar nav container --}}
                            </div>
                        </section>
                        {{-- END slimscroll sidebar nav content --}} {{-- BEGIN sidebar nav footer --}}
                        <footer class="footer lt hidden-xs b-t b-stc">
                            {{-- Toogle sidebar nav to small --}}
                            <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-danger btn-stc btn-icon">
                                <i class="fa fa-angle-left text"></i>
                                <i class="fa fa-angle-right text-active"></i>
                            </a>
                        </footer>
                        {{-- END siebar nav footer --}}
                    </section>
                    {{-- END vbox inside sidebar nav --}}
                </aside>
                {{-- END sidebar nav --}} {{-- BEGIN content --}}
                <section id="content">
                    {{-- BEGIN vbox content --}}
                    <section class="vbox">
                        {{-- BEGIN content scrollable --}}
                        <section class="scrollable padder">
                            {{-- BEGIN breadcrumb --}}
                            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                                <li>
                                    <a href="{{ URL::to(" / ") }}">
                                        <i class="fa fa-home"></i> Home</a>
                                </li>
                                @yield('breadcrumb')
                            </ul>
                            {{-- END breadcrumb --}} {{-- BEGIN Alert Messages--}} @if (Session::has('success-message'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">Ã—</button>
                                <h4>
                                    <i class="fa fa-info-circle m-r-sm"></i>Hoooray!</h4>
                                <p>{{ Session::get('success-message') }}</p>
                            </div>
                            @elseif (Session::has('error-message'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">Ã—</button>
                                <h4>
                                    <i class="fa fa-warning m-r-sm"></i>Uh, Oh!</h4>
                                Something need to be fixed:
                                <p>{{ Session::get('error-message') }}</p>
                            </div>
                            @elseif (count($errors) > 0)
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">Ã—</button>
                                <h4>
                                    <i class="fa fa-warning m-r-sm"></i>Uh, Oh!</h4>
                                Something need to be fixed:
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif {{-- END Alert--}} @yield('content')
                        </section>
                        {{-- END content scrollable --}} {{-- Display footer if not using chrome --}} {{--@if (!isChrome())--}} {{--
                        <footer class="footer bg-white b-t b-light">--}} {{--
                            <p>We recommend
                                <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a> for best experience.</p>--}} {{--
                        </footer>--}} {{--@endif--}}

                    </section>
                    {{-- END vbox content --}} {{-- Link to hide off screen nav --}}
                    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
                </section>
                {{-- END content --}}
            </section>
            {{-- END hbox --}}
        </section>

        {{-- END content section --}}
    </section>
    {{-- END vbox section --}}
    <!-- jquery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <!-- App -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <script>
    </script> {{-- placeholder for page's javascript --}} @yield('pagejs')


</body>

</html>