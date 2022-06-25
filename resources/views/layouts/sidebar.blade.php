<div class="offcanvas offcanvas-start col-2" tabindex="-1" id="navbarToggleExternalContent" data-bs-keyboard="false"
     data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h6 class="offcanvas-title d-none d-sm-block" id="offcanvas"></h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-0">
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start" id="menu">
            <li class="nav-item dropdown w-100">
                <button class="dropdown-item" onclick="location.href='{{ route('home') }}'">
                    <img src="{{ asset("images/login-icon.svg") }}" alt="login"/>
                    Home
                </button>
            </li>
            @if(Auth::user() && Auth::user()->role === 'admin')
                <li class="nav-item dropdown w-100">
                    <button class="dropdown-item" onclick="location.href='{{ route('login') }}'">
                        <img src="{{ asset("images/login-icon.svg") }}" alt="login"/>
                        Categorie
                    </button>
                </li>
                <li class="nav-item dropdown w-100">
                    <button class="dropdown-item" onclick="location.href='{{ route('login') }}'">
                        <img src="{{ asset("images/login-icon.svg") }}" alt="login"/>
                        Sottocategorie
                    </button>
                </li>
                <li class="nav-item dropdown w-100">
                    <button class="dropdown-item" onclick="location.href='{{ route('login') }}'">
                        <img src="{{ asset("images/login-icon.svg") }}" alt="login"/>
                        Corsi
                    </button>
                </li>
                <li class="nav-item dropdown w-100">
                    <button class="dropdown-item" onclick="location.href='{{ route('admin-dashboard') }}'">
                        <img src="{{ asset("images/dashboard-icon.svg") }}" alt="dashboard"/>
                        Admin Dashboard
                    </button>
                </li>
            @endif
            @guest
                @if (Route::has('login'))
                    <li class="nav-item dropdown w-100">
                        <button class="dropdown-item" onclick="location.href='{{ route('login') }}'">
                            <img src="{{ asset("images/login-icon.svg") }}" alt="login"/>
                            Login
                        </button>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item dropdown w-100">
                        <button class="dropdown-item" onclick="location.href='{{ route('register') }}'">
                            <img src="{{ asset("images/register-icon.svg") }}" alt="register"/>
                            Registrati
                        </button>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown w-100">
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
    <div class="col-12 d-flex justify-content-center">
        @include('templates/logo')
    </div>
</div>

