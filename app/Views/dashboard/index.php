<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
        }
        .sidebar .nav-link:hover {
            background: #1a252f;
            color: white;
        }
        .sidebar .nav-link.active {
            background: #667eea;
            color: white;
        }
        .navbar-top {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 20px;
        }
        .content-wrapper {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .card-stats {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
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
                    <a class="nav-link active" href="<?= base_url('/dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/skp') ?>">
                        <i class="fas fa-file-alt me-2"></i> SKP / RHK
                    </a>
                </li>
                <?php if($role == 'super_admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/user') ?>">
                        <i class="fas fa-users me-2"></i> Manajemen User
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="<?= base_url('/logout') ?>">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="content-wrapper w-100">
            <nav class="navbar-top d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h5>
                <div class="dropdown">
                    <a href="#" class="text-dark text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-1"></i> <?= $nama ?>
                        <i class="fas fa-chevron-down ms-1 small"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </nav>
            
            <div class="p-4">
                <!-- Welcome Card -->
                <div class="alert alert-info fade-in">
                    <i class="fas fa-hand-wave me-2"></i>
                    Selamat datang, <strong><?= $nama ?></strong>! Anda login sebagai <span class="badge bg-primary"><?= $role ?></span>
                </div>
                
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card card-stats bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">SKP Aktif</h6>
                                        <h3 class="mb-0">1</h3>
                                    </div>
                                    <i class="fas fa-file-alt fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-stats bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">RHK Selesai</h6>
                                        <h3 class="mb-0">0</h3>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-stats bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">Menunggu Approval</h6>
                                        <h3 class="mb-0">0</h3>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Menu Cards -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                                <h5>SKP / RHK</h5>
                                <p class="text-muted">Buat dan kelola SKP serta RHK Anda</p>
                                <a href="<?= base_url('/skp') ?>" class="btn btn-primary">Kelola SKP</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                                <h5>Realisasi</h5>
                                <p class="text-muted">Input realisasi kinerja bulanan</p>
                                <a href="<?= base_url('/realisasi') ?>" class="btn btn-success">Input Realisasi</a>
                            </div>
                        </div>
                    </div>
                    <?php if($role == 'super_admin'): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-info mb-3"></i>
                                <h5>Manajemen User</h5>
                                <p class="text-muted">Tambah, edit, dan kelola user</p>
                                <a href="<?= base_url('/user') ?>" class="btn btn-info">Kelola User</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>