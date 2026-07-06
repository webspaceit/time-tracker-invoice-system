<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Invoice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-card { border-top: 4px solid #1D560B; }
        .login-btn { background-color: #1D560B; border-color: #1D560B; color: #fff; }
        .login-btn:hover { background-color: #143d08; border-color: #143d08; color: #fff; }
        .login-title { color: #1D560B; font-weight: 700; }
    </style>
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow login-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-file-invoice fa-3x mb-2" style="color:#1D560B;"></i>
                            <h4 class="login-title">Invoice System</h4>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label">Remember me</label>
                            </div>
                            <button type="submit" class="btn login-btn w-100">Sign in</button>
                        </form>
                        <p class="text-center text-muted small mt-3 mb-0">
                            Don't have an account?
                            <a href="{{ route('register') }}" style="color:#1D560B;">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
