<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-signin-client_id" content="">
    <title>{{ config('app.name', 'iShare') }}</title>
    <!-- Scripts -->
    @stack('head')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-multiselect.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @php
            $isAdmin=Auth::user() && Auth::user()->is_admin;
            $isUser=Auth::user() && Auth::user()->id;
            $isGuest=Auth::user() && !Auth::id();
        @endphp
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm ">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'iShare')}}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @if(Auth::check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('newArticle') }}">
                                Publish new Article <i class="far fa-newspaper"></i>
                            </a>
                        </li>
                        @endif
                        @if ($isUser || $isAdmin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('myArticle') }}">
                                My Articles <i class="fab fa-blogger"></i>
                                </a>
                        </li>
                        @endif
                        @if($isAdmin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.category') }}">
                                manage category <i class="fas fa-tools"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.home') }}">
                            Dashboard   <i class="fas fa-chart-line"></i> 
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                {{ __('Login') }} <i class="fas fa-sign-in-alt"></i>
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                    {{ __('Register') }} <i class="fas fa-user-plus"></i>
                                    </a>
                                </li>
                            @endif
                        @endguest
                        @if($isGuest)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        {{ __('Login') }} <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        {{ __('Register') }} <i class="fas fa-user-plus"></i>
                                    </a>
                                </li>
                            @endif
                            @if($isUser || $isAdmin)
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="fas fa-user-circle"></i> profile 
                                    </a>    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4" style="background-image: url(/image/background.jpg);">
            @yield('content')
        </main>
    </div>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="{{ asset('fontawesome/js/all.min.js') }}" defer></script>
    <script src="{{ asset('fontawesome/js/bootstrap-multiselect.js') }}" defer></script>
    @stack('script')
</body>
</html>
