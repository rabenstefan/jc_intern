<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jazzchor Bonn intern - @yield('title')</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    {!! Html::style('css/app.css') !!}
</head>
<body id="app-layout" data-spy="scroll" data-target="#scroll-spy-nav">
    <nav class="navbar navbar-default navbar-2d navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Jazzchor Bonn intern
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::check())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('nav.users') }}<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user') }}">{{ trans('nav.user_list') }}</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}&nbsp;<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>&nbsp;Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hidden-xs hidden-sm col-md-1">
            <div id="scroll-spy-nav" data-spy="affix">
                <nav class="sidebar-nav-2d">
                    @yield('navlist')
                </nav>
                {{--<a href="#" onclick="$('html,body').animate({scrollTop:0}, 500);return false;">
                    <i class="fa fa-caret-up"></i>&nbsp;{{ trans('nav.back_top') }}
                </a>--}}
            </div>
        </div>
        <div class="col-xs-12 col-md-10">
            @yield('content')
        </div>
    </div>

    <!-- JavaScripts -->
    {!! Html::script('js/jquery.min.js') !!}
    {!! Html::script('js/all.js') !!}
    <script type="text/javascript">
        $.notify.addStyle('shadow2d', {
            html: '<span data-notify-text/>'
        });
        $.notify.defaults({
            autoHide: true,
            autoHideDelay: 1500,
            style: 'shadow2d',
            showAnimation: 'show',
            showDuration: 0,
            hideAnimation: 'hide',
            hideDuration: 0
        });

        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                {{-- Display all global errors as danger-bubbles, hide them after 5 seconds to make sure the user sees them. --}}
                $.notify("{{ $error }}", {className: "danger", autoHideDelay: 5000});
            @endforeach
        @endif
        @foreach (['danger', 'warning', 'success', 'info'] as $message_code)
            {{-- Display all global messages as bubbles. --}}
            @if (Session::has('message_'.$message_code))
                $.notify("{{ Session::get('message_'.$message_code) }}", "{{ $message_code }}");
            @endif
        @endforeach
    </script>
</body>
</html>
