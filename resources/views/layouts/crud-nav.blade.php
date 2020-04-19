<?php /* Header/Nav */ ?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark mb-0">
    <?php /* Text "logo" */ ?>
    <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.alias',"APP") }}</a>

    <?php /* Mobile menu button */ ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <?php /* Menus: left and right */ ?>
    <div class="collapse navbar-collapse" id="navbarCollapse">

        @if(Auth::check())
            <?php /* Left (main) menu items: */ ?>
            <ul class="navbar-nav mr-auto">

                @can(['organization index'])
                    <li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'organization') echo 'active' @endphp">
                        <a class="nav-link" href="{{ route('organization.index') }}">organizations <span
                                class="sr-only">(current)</span></a>
                    </li>
                @endcan


                {{-- Admin Panel--}}
                @canany(['invite index'])
                    <li class="nav-item dropdown @php if(isset($nav_path[0]) && $nav_path[0] == 'admin') echo 'active' @endphp">
                        <a class="nav-link dropdown-toggle" href="#TODO" id="dropdown-admin" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Admin</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-admin">
                            @can(['city_neighborhood index'])
                                <a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'city-neighborhood') echo 'active' @endphp"
                                   href="/city-neighborhood">City Neighborhoods</a>
                            @endcan

                        </div>
                    </li>
                @endcanany


                {{-- LBV Super Admin  Panel--}}

                @canany(['invite index'])
                    <li class="nav-item dropdown @php if(isset($nav_path[0]) && $nav_path[0] == 'admin') echo 'active' @endphp">
                        <a class="nav-link dropdown-toggle" href="#TODO" id="dropdown-admin" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">C4KC</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-admin">

                            @can(['invite index'])
                                <a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'invite') echo 'active' @endphp"
                                   href="/invite">Invite Users</a>
                            @endcan


                            @can(['user index'])
                                <a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'user') echo 'active' @endphp"
                                   href="/user">Users</a>
                            @endcan

                        </div>
                    </li>
                @endcanany


            </ul>

            <?php /* Right (auxiliary) menu items: */ ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown @php if(isset($nav_path[0]) && $nav_path[0] == 'meta') echo 'active' @endphp">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="dropdown-current-user"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Welcome, {{ Auth::user()->name }}!
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown-current-user">
                        <a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == 'change-password') echo 'active' @endphp"
                           href="/change-password">Change Password</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </div>
                </li>
            </ul>

            <?php /* Apparently Laravel does logout route via post; get not accepted */ ?>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                {{ csrf_field() }}
                {{ method_field('POST') }}
            </form>
        @else
            <?php /* Right (auxiliary) menu items: */ ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == 'login') echo 'active' @endphp">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
            </ul>
        @endif

    </div>
</nav>
