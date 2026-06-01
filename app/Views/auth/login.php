<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        padding: 30px;
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header h3 {
        color: #667eea;
    }

    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        width: 100%;
        padding: 10px;
        color: white;
        border-radius: 10px;
    }

    .alert {
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <h3>SIMKIN UIN Salatiga</h3>
            <p>Sistem Informasi Kinerja Pegawai</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('/auth/login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                    autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>

        <hr class="my-4">
        <div class="text-center text-muted small">
            <p>UIN Salatiga - 2026</p>
            <p class="mb-0">Akun demo: superadmin / password</p>
        </div>
    </div>
</body>

</html>