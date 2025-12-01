<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Retali Mustajab Travel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            height: 100vh;
            margin: 0;
            padding: 0;
            background: #ffffff; /* Background putih */
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
        }

        .login-card {
            background: white;
            border-radius: 18px;
            padding-bottom: 30px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid #eee;
        }

        .login-header {
            text-align: center;
            padding: 30px 20px 20px;
        }

        .login-header img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }

        .welcome-text {
            font-size: 15px;
            color: #555;
        }

        .login-body {
            padding: 25px 30px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ccc;
            transition: 0.25s ease;
        }

        .form-control::placeholder {
            color: #888;
        }

        .form-control:focus {
            border-color: #6C63FF;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #6C63FF, #3A0CA3);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            font-size: 15px;
            transition: 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #7d74ff, #4a15c7);
            box-shadow: 0 7px 20px rgba(0,0,0,0.25);
        }

        .invalid-feedback {
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">

            <!-- Header -->
            <div class="login-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Retali">
                <div class="brand-name">Retali Mustajab Travel</div>
                <div class="welcome-text">Masukkan akun anda</div>
            </div>

            <!-- Form -->
            <div class="login-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               placeholder="Masukkan email anda" required autofocus>

                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Masukkan password anda" required>

                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login">LOGIN</button>

                </form>
            </div>

        </div>
    </div>

</body>
</html>
