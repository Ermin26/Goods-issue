<header>
    <nav id="navbar" class="navbar navbar-dark navbar-expand-md bg-dark sticky-top text-light">
        <div class="container-fluid bg-dark">
            <a class="navbar-brand" href="/">Novi Račun</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-evenly" id="navbarNav">
                <ul class="navbar-nav d-flex w-100 me-auto">
                    @if(auth()->check())
                        <li class="nav-item m-1 p-0">
                            <a href="/all"><button class="btn btn-primary p-1" id="btn-func">Računi</button></a>
                        </li>

                        <li class="nav-item m-1">
                            <a href="/search"><button class="btn btn-outline-info p-1 text-light" id="btn-func">Išči</button></a>
                        </li>
                        <li class="nav-item m-1">
                            <a href="/costs"><button class="btn btn-danger p-1" id="btn-func">Stroški</button></a>
                        </li>
                        <li class="nav-item m-1">
                            <a href="/vacation"><button class="btn btn-info p-1" id="btn-func">Dopust</button></a>
                        </li>
                        <li class="nav-item dropdown m-1">
                            <a href="/users"><button type="button" class="btn btn-info p-1">Uporabniki</button></a>
                            <button type="button" class="btn btn-info dropdown-toggle  dropdown-toggle-split p-1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item">
                                    <a href="/users/add"><button class="btn btn-success p-1" id="btn-func">Dodaj delavca</button></a>
                                </li>

                                <li class="dropdown-item">
                                    <a href="/users/register"><button class="btn btn-success p-1" id="btn-func">Dodaj uporabnika</button></a>
                                </li>

                            </ul>
                        </li>
                            @if($notifications && count($notifications) > 0)
                                <li id="iconLi" class="nav-item dropdown dropstart ms-auto me-right p-0 text-end">
                                        <div id="notificationIcon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red"
                                                class="bi bi-bell" viewBox="0 0 22 22">
                                                <path
                                                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                                            </svg>
                                            <div class="numLength">
                                                <p class="num">{{count($notifications)}}</p>
                                            </div>
                                        </div>
                                        <ul id="notifications">
                                            @foreach ($notifications as $notification)
                                                <li>
                                                    <a href="/vacation">
                                                       Delavec oddal vlogo za dopust.
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                </li>
                            @else
                                <li id="iconLi" class="nav-item dropdown dropstart ms-auto me-right p-0 text-end">
                                    <div id="notificationIcon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white"
                                            class="bi bi-bell" viewBox="0 0 22 22">
                                            <path
                                                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                                        </svg>
                                    </div>
                                </li>
                            @endif
                        @else
                            <li class="ms-auto">
                            <a href="/login" class="btn btn-outline-info m-1 p-2">Prijava</a>
                            </li>
                        @endif
                        @if(auth()->check())
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-info" style="display: inline; cursor: pointer;">Logout</button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
    </nav>
    @if(Auth::check())
        <div id="userInfo" class="row d-flex flex-row flex-nowrap">
            <div class="name btn btn-sm btn-primary">User: {{Auth::user()->name}}</div>
            <div class="role btn btn-sm btn-primary">Role: {{Auth::user()->role}}</div>
        </div>
    @endif

</header>