<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>
            @section('title')
            Thesaurus Manager Application
            @show
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @section('css')
        <link href="{{{ asset('assets/css/bootstrap.min.css') }}}" rel="stylesheet">
        @show
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- Favicons
        ================================================== -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
        <link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
        <link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">
    </head>

    <body>
        <!-- Navbar -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Thesaurus</a>
                </div>


                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li {{{ (Request::is('/') ? 'class="active"' : '') }}}><a href="{{{ URL::to('') }}}">Home</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Words <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/nodes/add">Add</a></li>
                                <li><a href="/nodes/search">Search</a></li>
                                <li><a href="/nodes/graph-editor/1">Graph Editor Demo</a></li>
                            </ul>

                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right ">
                        @if (Auth::check()) 
                        <li {{{ (Request::is('account') ? 'class="active"' : '') }}}>
                            <a href="{{{ URL::to('account') }}}">Logged in as {{{ Auth::user()->fullName() }}}</a>
                        </li>
                        <li><a href="{{{ URL::to('account/logout') }}}">Logout</a></li>
                        @else
                        <li {{{ (Request::is('account/login') ? 'class="active"' : '') }}}>
                            <a href="{{{ URL::to('account/login') }}}">Login</a>
                        </li>
                        <li {{{ (Request::is('account/register') ? 'class="active"' : '') }}}>
                            <a href="{{{ URL::to('account/register') }}}">Register</a>
                        </li>
                        @endif
                    </ul>
                </div>
                <!-- ./ nav-collapse -->
            </div>
        </div>
        <!-- ./ navbar -->

        <div class="container">
            @include('notifications')

            @yield('content')
        </div>
        @section('js')
        <script src="{{{ asset('assets/js/jquery.min.js') }}}"></script>
        <script src="{{{ asset('assets/js/bootstrap.min.js') }}}"></script>
        @show

    </body>
</html>
