<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1a365d 70%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 70% 50%, rgba(99, 102, 241, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 40px 36px 36px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-brand .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(30, 58, 95, 0.3);
        }

        .login-brand .brand-icon i {
            font-size: 28px;
            color: #ffffff;
        }

        .login-brand h3 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .login-brand p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 0;
        }

        .login-brand p i {
            color: #2563eb;
            margin-right: 4px;
        }

        .form-floating-icon {
            position: relative;
            margin-bottom: 18px;
        }

        .form-floating-icon .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            z-index: 5;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-floating-icon .form-control {
            padding-left: 42px;
            height: 48px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-floating-icon .form-control:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-floating-icon .form-control:focus~.input-icon,
        .form-floating-icon .form-control:focus+.input-icon {
            color: #2563eb;
        }

        .form-floating-icon .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .form-floating-icon .form-floating-label {
            position: absolute;
            left: 42px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
            transition: all 0.2s;
            background: transparent;
            padding: 0 4px;
        }

        .form-floating-icon .form-control:focus~.form-floating-label,
        .form-floating-icon .form-control:not(:placeholder-shown)~.form-floating-label {
            top: -8px;
            left: 34px;
            font-size: 11px;
            color: #2563eb;
            background: #ffffff;
            padding: 0 4px;
        }

        .form-floating-icon .form-control::placeholder {
            color: transparent;
        }

        .form-check-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            margin-top: -2px;
        }

        .form-check-custom .form-check-input {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1.5px solid #cbd5e1;
            cursor: pointer;
            margin-top: 0;
        }

        .form-check-custom .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .form-check-custom .form-check-label {
            font-size: 14px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            font-size: 16px;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .login-footer p {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .login-footer .text-muted {
            color: #94a3b8 !important;
        }

        .already-logged-in {
            text-align: center;
            margin-top: 16px;
        }

        .already-logged-in a {
            color: #64748b;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .already-logged-in a:hover {
            color: #2563eb;
        }

        .already-logged-in a i {
            margin-right: 4px;
        }

        .alert-custom {
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            border: none;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom.alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        .alert-custom.alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border-left: 4px solid #22c55e;
        }

        .alert-custom i {
            font-size: 16px;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">

            <?php if (session()->get('logged_in')): ?>
                <div class="already-logged-in">
                    <p class="mb-2" style="color: #475569; font-size: 14px;">Anda sudah login</p>
                    <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-primary btn-sm" style="border-radius: 8px; padding: 8px 24px;">
                        <i class="fas fa-arrow-right"></i> Ke Dashboard
                    </a>
                </div>
            <?php else: ?>

                <div class="login-brand">
                    <div class="brand-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>SIMKIN UIN Salatiga</h3>
                    <p><i class="fas fa-circle" style="font-size: 5px; vertical-align: middle;"></i> Sistem Monitoring Kinerja</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert-custom alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert-custom alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/auth/login') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <div class="form-floating-icon">
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" required autofocus>
                        <i class="fas fa-user input-icon"></i>
                        <label for="username" class="form-floating-label">Username</label>
                    </div>

                    <div class="form-floating-icon">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <label for="password" class="form-floating-label">Password</label>
                    </div>

                    <div class="form-check-custom">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>
                </form>

                <div class="login-footer">
                    <p class="text-muted">&copy; <?= date('Y') ?> UIN Salatiga</p>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
