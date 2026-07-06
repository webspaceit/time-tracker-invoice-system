<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up — Invoice System</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon-96x96.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .auth-card { border-top: 4px solid #1D560B; }
        .auth-btn { background-color: #1D560B; border-color: #1D560B; color: #fff; }
        .auth-btn:hover { background-color: #143d08; border-color: #143d08; color: #fff; }
        .auth-title { color: #1D560B; font-weight: 700; }
    </style>
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow auth-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-file-invoice fa-3x mb-2" style="color:#1D560B;"></i>
                            <h4 class="auth-title">Create Account</h4>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ old('name') }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" required>
                            </div>
                            <button type="submit" class="btn auth-btn w-100">Sign up</button>
                        </form>
                        <p class="text-center text-muted small mt-3 mb-0">
                            Already have an account?
                            <a href="{{ route('login') }}" style="color:#1D560B;">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
