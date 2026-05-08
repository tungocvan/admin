<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="login-page bg-body-secondary">

{{-- <div class="login-box">
    <div class="login-logo mb-3">
        <b>FlexBiz</b> Admin
    </div>

    <div class="card shadow-sm">
        <div class="card-body login-card-body">

            <form method="POST" action="{{ route('login.perform') }}">
                @csrf

                <div class="mb-3">
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email"
                           value="{{ old('email') }}"
                           required autofocus>
                </div>

                <div class="mb-3">
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Mật khẩu"
                           required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Đăng nhập
                    </button>
                </div>
            </form>

        </div>
    </div>
</div> --}}
<div class="wrapper">
    <div class="icon">
        <img src="https://www.freepnglogos.com/uploads/512x512-logo-png/512x512-logo-github-icon-35.png" alt="">
    </div>
    <div class="text-center mt-4 name"> FlexBiz </div>

    <form class="p-3 mt-3" method="POST" action="{{ route('login.perform') }}">
        @csrf
        <div class="input-field d-flex align-items-center">
            <span class="far fa-user"></span> <input type="email" name="email" id="userName" placeholder="Email" value="{{ old('email') }}">
        </div>
        <div class="input-field d-flex align-items-center"> <span class="fas fa-key"></span>
            <input type="password" name="password" id="pwd" placeholder="Password">
         </div>
          <button class="btn mt-3">Login</button>
    </form>
    <div class="text-center fs-6">
        <a href="forgot-password.html">Forgot password?</a> or <a href="register.html">Sign up</a>
    </div>
</div>
</body>
</html>
