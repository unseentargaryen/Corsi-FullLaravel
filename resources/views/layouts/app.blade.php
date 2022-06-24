<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- CUSTOMS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <div class="btn-group dropdown position-absolute" style="right:3%">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('images/account.svg') }}" width="45px" height="45px"/>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item dropdown">
                                <button class="dropdown-item" onclick="location.href='{{ route('login') }}'">
                                    <img src="{{ asset("images/login-icon.svg") }}" alt="login"/>
                                    Login
                                </button>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item dropdown">
                                <button class="dropdown-item" onclick="location.href='{{ route('register') }}'">
                                    <img src="{{ asset("images/register-icon.svg") }}" alt="register"/>
                                    Registrati
                                </button>
                            </li>
                        @endif
                    @else
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item dropdown">
                                <button class="dropdown-item" onclick="location.href='{{ route('admin-dashboard') }}'">
                                    <img src="{{ asset("images/dashboard-icon.svg") }}" alt="dashboard"/>
                                    Admin Dashboard
                                </button>
                            </li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="nav-item dropdown">
                            <button class="dropdown-item" type="button" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); $('#logout-form').submit()">
                                <img src="{{ asset("images/logout-icon.svg") }}" alt="logout"/>
                                Logout
                            </button>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
