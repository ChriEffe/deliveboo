<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Deliverboo') }}</title>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="{{ asset('js/front.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style scoped>
        .nav-pills .nav-link.active {
            background-color: #38c172;
        }

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Deliverboo') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav ml-auto w-100 justify-content-between">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ 'Login' }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ 'Register' }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown mx-4" style="margin-top: 7px;">Name: {{ Auth::user()->name }}</li>
                        <li class="nav-item dropdown">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="submit" value="Logout" class="btn btn-default">
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="bg-dark">
        <div class="container-fluid">
            <div class="row">
                <div class="col-3 border-end border-warning border-4">
                    <div style="height: 100vh" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark w-100">
                        <a href="{{ route('admin.users.index') }}"
                            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                            <img class="bi me-2 w-50 align-center" src="{{ asset('img/logo.jpeg') }}" alt="Logo"
                                class="rounded-circle me-2">
                        </a>
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <a href="{{ route('admin.dishes.index') }}"
                                    class="{{ 'admin.dishes.index' === Route::currentRouteName() ? 'active' : '' }} nav-link text-white"
                                    aria-current="page">
                                    <lord-icon src="https://cdn.lordicon.com/coqbeapw.json" trigger="loop"
                                        colors="primary:#e8b730,secondary:#08a88a" stroke="60"
                                        style="width:50px;height:50px" delay="5000">
                                    </lord-icon>
                                    Visualizza tutti i piatti
                                </a>
                            </li>
                            {{-- @dd(Route::currentRouteName()) --}}
                            <li>
                                <a href="{{ route('admin.dishes.create') }}"
                                    class="{{ 'admin.dishes.create' === Route::currentRouteName() ? 'active' : '' }} nav-link text-white">
                                    <lord-icon src="https://cdn.lordicon.com/mecwbjnp.json" trigger="loop"
                                        colors="primary:#e8b730,secondary:#08a88a" stroke="75"
                                        style="width:50px;height:50px" delay="5000">
                                    </lord-icon>
                                    Aggiungi piatto
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="{{ 'admin.orders.index' === Route::currentRouteName() ? 'active' : '' }} nav-link text-white">
                                    <lord-icon src="https://cdn.lordicon.com/cjieiyzp.json" trigger="loop"
                                        colors="primary:#e8b730,secondary:#08a88a" style="width:50px;height:50px"
                                        delay="5000">
                                    </lord-icon>
                                    Vedi i tuoi ordini
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="{{ 'admin.users.index' === Route::currentRouteName() ? 'active' : '' }} nav-link text-white">
                                    <lord-icon src="https://cdn.lordicon.com/dxjqoygy.json" trigger="loop"
                                        colors="primary:#e8b730,secondary:#08a88a" style="width:50px;height:50px"
                                        delay="5000">
                                    </lord-icon>
                                    Vedi il tuo profilo
                                </a>
                            </li>
                        </ul>
                        <hr>
                        {{-- <div class="dropdown">
                            <a href="#"
                                class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ $user->photo }}" alt="" width="32" height="32"
                                    class="rounded-circle me-2">
                                <strong>mdo</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="#">New project...</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Sign out</a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>

                <div class="col">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</body>
<script src="https://cdn.lordicon.com/lusqsztk.js"></script>

</html>
