<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat SKP - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .sidebar {
        min-height: 100vh;
        background: #2c3e50;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
    }

    .sidebar .nav-link:hover {
        background: #1a252f;
        color: white;
    }

    .navbar-top {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 15px 20px;
    }

    .content-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
    }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 260px;">
            <div class="p-4 text-center text-white border-bottom border-secondary">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-0">SIMKIN UIN</h5>
                <small>Salatiga</small>
            </div>
            <ul class="nav flex-column p-3">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/skp') ?>">
                        <i class="fas fa-file-alt me-2"></i> SKP / RHK
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="<?= base_url('/logout') ?>">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper w-100">
            <nav class="navbar-top">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Buat SKP Baru</h5>
            </nav>

            <div class="p-4">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda akan membuat SKP untuk periode tahun <?= date('Y') ?>.
                        </div>

                        <form action="<?= base_url('/skp/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Pembuatan SKP</label>
                                <p class="text-muted">Klik tombol di bawah untuk membuat SKP baru.</p>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Buat SKP
                            </button>
                            <a href="<?= base_url('/skp') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>