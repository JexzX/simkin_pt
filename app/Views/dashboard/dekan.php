<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'dashboard']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Dekan
                <?= session()->get('unit_kerja') ?></h5>
            <div class="d-flex align-items-center">
                <div class="dropdown me-3">
                    <a href="#" class="text-dark" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="badge bg-danger rounded-pill" id="notifikasi-badge"
                            style="display: none;"><?= $unreadCount ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <h6 class="dropdown-header">Notifikasi</h6>
                        <?php foreach($notifikasi as $n): ?>
                        <a class="dropdown-item" href="javascript:markNotifikasiRead(<?= $n['id'] ?>)">
                            <small><strong><?= $n['judul'] ?></strong></small><br>
                            <small class="text-muted"><?= $n['pesan'] ?></small>
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
                        <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>"><i
                                    class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="p-4">
            <!-- Statistik -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">Target Akreditasi Fakultas</h6>
                                    <h3 class="mb-0">4 / 6 Prodi</h3>
                                    <small>Target Unggul</small>
                                </div>
                                <i class="fas fa-trophy fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">SKP Kaprodi Menunggu</h6>
                                    <h3 class="mb-0"><?= count($skpMenunggu) ?></h3>
                                </div>
                                <i class="fas fa-clock fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKP Pribadi -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i> SKP Dekan <?= date('Y') ?></h6>
                    <?php if(empty($skpDekan)): ?>
                    <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary-custom btn-sm">
                        <i class="fas fa-plus me-1"></i> Buat SKP
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($skpDekan)): ?>
                    <?php foreach($skpDekan as $skp): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <strong>Periode: <?= $periodeAktif['nama_periode'] ?? '-' ?></strong><br>
                            <small>Status: <span
                                    class="badge bg-<?= $skp['status'] == 'disetujui' ? 'success' : ($skp['status'] == 'menunggu_approval' ? 'warning' : 'secondary') ?>"><?= $skp['status'] ?></span></small>
                        </div>
                        <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                            class="btn btn-sm btn-outline-primary">Detail</a>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada SKP</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>