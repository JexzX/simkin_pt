<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'dashboard']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h5>
                <span class="badge bg-info text-white"><?= ucfirst($role) ?></span>
            </nav>
            <div class="p-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info d-flex justify-content-between align-items-center py-3 mb-0">
                            <div>
                                <h5 class="mb-1">Selamat datang, <strong><?= $nama ?></strong>!</h5>
                                <p class="mb-0 small">Unit: <strong><?= $unit_kerja ?></strong> 
                                <?php if($periodeAktif): ?>
                                    | Periode: <strong><?= $periodeAktif['nama_periode'] ?> (<?= $periodeAktif['tahun'] ?>)</strong>
                                <?php else: ?>
                                    | <span class="text-warning">Belum ada periode aktif</span>
                                <?php endif; ?>
                                </p>
                            </div>
                            <a href="<?= base_url('/profil') ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-cog"></i> Profil</a>
                        </div>
                    </div>
                </div>

                <?php if($periodeAktif): ?>
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs text-primary text-uppercase mb-1">Total SKP</div>
                                        <div class="h3 mb-0 font-weight-bold"><?= $totalSkp ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-file-alt fa-2x text-primary opacity-25"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs text-success text-uppercase mb-1">Disetujui</div>
                                        <div class="h3 mb-0 font-weight-bold"><?= $totalDisetujui ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-success opacity-25"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs text-warning text-uppercase mb-1">Menunggu</div>
                                        <div class="h3 mb-0 font-weight-bold"><?= $totalDiajukan ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-clock fa-2x text-warning opacity-25"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs text-info text-uppercase mb-1">Draft</div>
                                        <div class="h3 mb-0 font-weight-bold"><?= $totalDraft ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-pen fa-2x text-info opacity-25"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(in_array($role, ['rektor', 'dekan', 'kaprodi', 'super_admin'])): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs text-danger text-uppercase mb-1">Approval Pending</div>
                                        <div class="h3 mb-0 font-weight-bold"><?= $approvalPending ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-tasks fa-2x text-danger opacity-25"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fas fa-th-large me-2"></i>Menu Cepat</h5>
                    </div>
                    <?php if(in_array($role, ['rektor', 'dekan', 'kaprodi', 'dosen', 'super_admin', 'admin_perencana'])): ?>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/skp') ?>" class="text-decoration-none">
                            <div class="card card-hover border-primary h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">SKP / RHK</h5>
                                    <p class="card-text small text-muted">Buat dan kelola SKP & RHK</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if(in_array($role, ['rektor', 'dekan', 'kaprodi', 'dosen', 'super_admin', 'admin_perencana'])): ?>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/realisasi') ?>" class="text-decoration-none">
                            <div class="card card-hover border-success h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Realisasi</h5>
                                    <p class="card-text small text-muted">Input realisasi kerja per bulan</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if(in_array($role, ['rektor', 'dekan', 'kaprodi', 'super_admin'])): ?>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/approval/skp') ?>" class="text-decoration-none">
                            <div class="card card-hover border-warning h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-check-double fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">Persetujuan SKP</h5>
                                    <p class="card-text small text-muted">Setujui atau tolak SKP bawahan</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/approval/realisasi') ?>" class="text-decoration-none">
                            <div class="card card-hover border-info h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-clipboard-check fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">Persetujuan Realisasi</h5>
                                    <p class="card-text small text-muted">Setujui realisasi bawahan</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/penilaian') ?>" class="text-decoration-none">
                            <div class="card card-hover border-secondary h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-star fa-3x text-secondary mb-3"></i>
                                    <h5 class="card-title">Penilaian SKP</h5>
                                    <p class="card-text small text-muted">Nilai hasil kerja bawahan</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/laporan/skp') ?>" class="text-decoration-none">
                            <div class="card card-hover border-dark h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-dark mb-3"></i>
                                    <h5 class="card-title">Laporan SKP</h5>
                                    <p class="card-text small text-muted">Rekap & export data SKP</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/laporan/realisasi') ?>" class="text-decoration-none">
                            <div class="card card-hover border-dark h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-chart-bar fa-3x text-dark mb-3"></i>
                                    <h5 class="card-title">Laporan Realisasi</h5>
                                    <p class="card-text small text-muted">Rekap & export realisasi</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <?php if($role == 'admin_perencana' || $role == 'super_admin'): ?>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/periode') ?>" class="text-decoration-none">
                            <div class="card card-hover border-primary h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Periode</h5>
                                    <p class="card-text small text-muted">Kelola periode penilaian</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if($role == 'super_admin'): ?>
                    <div class="col-md-4 mb-3">
                        <a href="<?= base_url('/user') ?>" class="text-decoration-none">
                            <div class="card card-hover border-danger h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-users fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">Manajemen User</h5>
                                    <p class="card-text small text-muted">Kelola user & role</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <style>
        .border-left-primary { border-left: 4px solid #4e73df; }
        .border-left-success { border-left: 4px solid #1cc88a; }
        .border-left-warning { border-left: 4px solid #f6c23e; }
        .border-left-info { border-left: 4px solid #36b9cc; }
        .border-left-danger { border-left: 4px solid #e74a3b; }
        .opacity-25 { opacity: 0.25; }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
