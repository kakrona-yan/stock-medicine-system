
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-3 static-top" style="background-color: #e2e2e2 !important;">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-circle">
        <i class="fas fa-bars fa-fw fa-lg"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle text-capitalize" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fas fa-circle text-success" style="position: absolute;top: 7px;right: 5px;"></span>
                <span class="mr-2 d-none d-lg-inline text-gray-600">{{ Auth::user() ? Auth::user()->name : '' }}</span>
                <img class="img-profile rounded-circle" src="{{ Auth::user() && Auth::user()->thumbnail? getUploadUrl(Auth::user()->thumbnail, config('upload.user')) : asset('images/avatar.png') }}" width="45"/>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#">
                <i class="fas fa-cogs fa-fw fa-lg mr-2 text-gray-400"></i>Settings
            </a>
            <div class="dropdown-divider"></div>
                <a class="dropdown-item"
                        href="{{route('logout')}}"
                        onclick="event.preventDefault();
                        document.getElementById('form-logout').submit();"
                ><i class="fas fa-sign-out-alt fa-lg fa-fw mr-2 text-gray-400"></i>Log out</a>
                <form id="form-logout" action="{{route('logout')}}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
