{{-- <li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <img src="/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow"
            alt="User Image" />
        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <!-- User image -->
        <li class="user-header text-bg-primary">
            <img src="/assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="User Image" />

            <p>
                {{ auth()->user()->name }} - Web Developer
                <small>Member since Nov. 2023</small>
            </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">
            <!--begin::Row-->
            <div class="row">
                <div class="col-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </div>
            <!--end::Row-->
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <a href="#" class="btn btn-default btn-flat">Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>

        </li>
    </ul>
</li> --}}
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" style="line-height:0px">
        <i class="bi bi-person-circle fs-4"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 240px;">
        <!-- HEADER -->
        <div class="dropdown-header">
            <h6 class="mb-0">{{ auth()->user()->name }} </h6>
            <small class="text-muted">{{ auth()->user()->email }} </small>
            <span class="badge bg-success-subtle text-success mt-1">Pro Plan</span>
        </div>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-person me-2"></i>
            <span>Profile & Settings</span>
        </a>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-building me-2"></i>
            <span>Workspace Settings</span>
        </a>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-credit-card me-2"></i>
            <span>Billing & Usage</span>
        </a>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-people me-2"></i>
            <span>Team Management</span>
        </a>

        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-headset me-2"></i>
            <span>Support Center</span>
        </a>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-keyboard me-2"></i>
            <span>Keyboard Shortcuts</span>
        </a>

        <a href="#" class="dropdown-item d-flex align-items-center">
            <i class="bi bi-download me-2"></i>
            <span>Download Mobile App</span>
        </a>

        <div class="dropdown-divider"></div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>
</li>
