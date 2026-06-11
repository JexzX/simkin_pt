<!DOCTYPE html>
<html>

<head>
    <title>Dashboard SIMKIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .sidebar {
        width: 260px;
        background: #2c3e50;
        min-height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
    }

    .sidebar a {
        color: white;
        display: block;
        padding: 12px 20px;
        text-decoration: none;
    }

    .sidebar a:hover {
        background: #1a252f;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
    }

    .sidebar .header {
        padding: 20px;
        text-align: center;
        color: white;
        border-bottom: 1px solid #444;
    }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="header">
            <h5>SIMKIN UIN</h5>
            <small>Salatiga</small>
        </div>
        <a href="<?= base_url('/dashboard') ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?= base_url('/skp') ?>">
            <i class="fas fa-file-alt"></i> SKP / RHK
        </a>
        <a href="<?= base_url('/realisasi') ?>">
            <i class="fas fa-chart-line"></i> Realisasi
        </a>

        <!-- MENU PERSETUJUAN SKP (UNTUK REKTOR, DEKAN, KAPRODI) -->
        <?php $userRole = session()->get('role'); ?>
        <?php if(in_array($userRole, ['rektor', 'dekan', 'kaprodi', 'super_admin'])): ?>
        <a href="<?= base_url('/approval/skp') ?>">
            <i class="fas fa-check-double"></i> Persetujuan SKP
        </a>
        <?php endif; ?>

        <?php if($userRole == 'super_admin'): ?>
        <a href="<?= base_url('/user') ?>">
            <i class="fas fa-users"></i> Manajemen User
        </a>
        <?php endif; ?>

        <a href="<?= base_url('/logout') ?>" style="color: #e74c3c; margin-top: 20px;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="content">
        <div class="alert alert-info">
            <h4>Selamat datang, <strong><?= $nama ?></strong>!</h4>
            <p>Anda login sebagai: <strong><?= $role ?></strong></p>
            <p>Unit Kerja: <strong><?= $unit_kerja ?></strong></p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>SKP / RHK</h5>
                        <a href="<?= base_url('/skp') ?>" class="btn btn-light">Kelola SKP</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Realisasi</h5>
                        <a href="<?= base_url('/realisasi') ?>" class="btn btn-light">Input Realisasi</a>
                    </div>
                </div>
            </div>
            <?php if($role == 'super_admin'): ?>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Manajemen User</h5>
                        <a href="<?= base_url('/user') ?>" class="btn btn-light">Kelola User</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tombol akses cepat ke Persetujuan SKP -->
        <div class="row mt-3">
            <div class="col-md-12">
                <a href="<?= base_url('/approval/skp') ?>" class="btn btn-warning">
                    <i class="fas fa-check-double"></i> Persetujuan SKP
                </a>
            </div>
        </div>
    </div>
</body>

</html>