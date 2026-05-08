<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Start navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
            <i class="bi bi-list"></i>
          </a>
        </li>
        <li class="nav-item d-none d-md-block">
          <a href="#" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-md-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>
      <!-- End navbar links -->
        {{-- Right Menu --}}
        <ul class="navbar-nav ms-auto me-3">
            @include('layouts.partials.navbar.search')
            @include('layouts.partials.navbar.language')
            @include('layouts.partials.navbar.messages')
            @include('layouts.partials.navbar.notifications')
            @include('layouts.partials.navbar.fullscreen')
            @include('layouts.partials.navbar.profile')
        </ul>

    </div>
</nav>
