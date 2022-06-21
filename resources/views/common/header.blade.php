<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark pl-0 pr-2 pb-2">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
            <div class="d-flex">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </div>
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link pt-1 px-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user" style="color:black;"></i>
                            <div class="media-body ml-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small font-weight-bold">@auth {{Auth::user()->name}} @else {{"Guest"}} @endauth</span>
                            </div>
                        </div>
                        <div class="media d-flex align-items-center">
                            <i class="fas" style="color:black;"></i>
                            <i class="fas" style="color:black;"></i>
                            <i class="fas" style="color:black;"></i>
                            <div class="media-body ml-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small font-weight-bold">@auth {{isset(Auth::user()->office) ? Auth::user()->office->name : ''}} @else {{""}} @endauth</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-right mt-2">
                        <a class="dropdown-item font-weight-bold" href="{{route('user.profile')}}"><span class="far fa-user-circle"></span>My Profile</a>
                        @if(Auth::user()->type == 2)
                        <a class="dropdown-item font-weight-bold" href="{{route('password.change')}}"><span class="far fa-address-card"></span>Change Password</a>
                        @endif
                        <div role="separator" class="dropdown-divider"></div>
                        <form method="POST" action="{{route('logout')}}">
                        @csrf
                        <button type="submit" class="dropdown-item font-weight-bold" ><span class="fas fa-sign-out-alt text-danger"></span>Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
