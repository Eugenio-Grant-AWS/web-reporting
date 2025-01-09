<header class="p-4 mb-3 bg-white z-3 border-bottom sticky-top">
    <div class="container-fluid">
        <div class="row-gap-2 align-items-center d-flex flex-sm-row gy-3 justify-content-between">
            <div class="col-sm-6">
                <div class="gap-3 d-flex align-items-center">
                    <a href=""
                        class="text-black rounded shadow-xl toggle d-inline-block d-md-none d-flex align-items-center justify-content-center">
                        <i class="fas fa-bars"></i></a>
                    <div class="path d-none d-md-block ">
                        {{-- <p>Home / <span>{{ $breadcrumb ?? 'Default Page' }}</span></p> --}}
                        <p>
                            <a href="{{ route('reach-exposure-probability-with-mean') }}"
                                style="text-decoration: none; color: inherit;">Home</a> /
                            @if (session('previous_url'))
                                <a href="{{ session('previous_url') }}"
                                    style="text-decoration: none; color: inherit;">{{ $breadcrumb ?? '' }}</a>
                            @else
                                <span>{{ $breadcrumb ?? 'Default Page' }}</span>
                            @endif
                        </p>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="admin-profile">
                    <ul class="gap-4 d-flex justify-content-end">
                        <!-- <li><a href="#"><i class="fas fa-cog"></i></a></li>
                        <li><a href="#"><i class="fas fa-bell"></i></a></li> -->
                        <!-- <li class="dropdown">
                            <a href="#" id="userDropdown">
                                <span>UserName</span>
                                <img class="ms-1" src="{{ asset('assets/images/profile-image.png') }}" alt="">
                            </a>
                            <ul class="rounded profile-dropdown" style="display: none;">
                                <li class="p-2"><a href="#">Profile</a></li>
                                <li class="p-2"><a href="#">Account</a></li>

                                <li class="p-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-transparent border-0 logout-button">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span>
                                {{-- <img class="ms-1" src="{{ asset('assets/images/profile-image.png') }}"
                                    alt="Profile Image"> --}}
                                <img class="ms-1"
                                    src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('assets/images/profile-image.png') }}"
                                    alt="Profile Image">

                            </a>
                            <ul class="dropdown-menu rounded profile-dropdown" aria-labelledby="userDropdown">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
        <div class="mt-4 path d-block d-md-none">
            <p>Home / <span>Reach Exposure / Probability with mean</span></p>
        </div>
    </div>
</header>
