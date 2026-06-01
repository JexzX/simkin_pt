<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'dashboard']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Administrator</h5>
            <div class="d-flex align-items-center">
                <div class="dropdown me-3">
                    <a href="#" class="text-dark" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="badge bg-danger rounded-pill" id="notifikasi-badge" style="display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <h6 class="dropdown-header">Notifikasi</h6>
                        <?php foreach($notifikasi as $n): ?>
                        <a class="dropdown-item" href="javascript:markNotifikasiRead(<?= $n['id'] ?>)">
                            <small><strong><?= $n['judul'] ?></strong></small><br>
                            <small class="text-muted"><?= $n['pesan'] ?></small>
                            <small
                                class="text-muted d-block"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="text-dark text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-1"></i> <?= session()->get('nama_lengkap') ?>
                        <i class="fas fa-chevron-down ms-1 small"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>"><i
                                    class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="p-4">
            <!-- Statistik Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-stats bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">Total SKP</h6>
                                    <h3 class="mb-0"><?= $totalSkp ?></h3>
                                </div>
                                <i class="fas fa-file-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">Menunggu Persetujuan</h6>
                                    <h3 class="mb-0"><?= $skpMenunggu ?></h3>
                                </div>
                                <i class="fas fa-clock fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">SKP Disetujui</h6>
                                    <h3 class="mb-0"><?= $skpDisetujui ?></h3>
                                </div>
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">SKP Draft</h6>
                                    <h3 class="mb-0"><?= $skpDraft ?></h3>
                                </div>
                                <i class="fas fa-pencil-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Periode Aktif -->
            <div class="alert alert-info">
                <i class="fas fa-calendar-alt me-2"></i>
                Periode Aktif: <strong><?= $periodeAktif['nama_periode'] ?? 'Belum ada periode aktif' ?></strong>
                (<?= $periodeAktif['tanggal_mulai'] ?? '-' ?> s/d <?= $periodeAktif['tanggal_selesai'] ?? '-' ?>)
            </div>

            <!-- Quick Links -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-database fa-3x text-primary mb-3"></i>
                            <h5>Master Data</h5>
                            <p class="text-muted">Kelola SP, SK, dan IKSK</p>
                            <a href="<?= base_url('/master/sp') ?>" class="btn btn-primary-custom btn-sm">Kelola
                                Master</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5>Manajemen User</h5>
                            <p class="text-muted">Tambah, edit, dan kelola user</p>
                            <a href="<?= base_url('/user') ?>" class="btn btn-primary-custom btn-sm">Kelola User</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-print fa-3x text-primary mb-3"></i>
                            <h5>Laporan</h5>
                            <p class="text-muted">Cetak laporan SKP dan realisasi</p>
                            <a href="<?= base_url('/laporan/skp') ?>" class="btn btn-primary-custom btn-sm">Lihat
                                Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>