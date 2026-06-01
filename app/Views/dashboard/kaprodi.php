<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'dashboard']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Kaprodi</h5>
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
                        <a class="dropdown-item"
                            href="javascript:markNotifikasiRead(<?= $n['id'] ?>)"><?= $n['judul'] ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="text-dark text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-1"></i> <?= session()->get('nama_lengkap') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Target Akreditasi Prodi</h6>
                            <h3>Unggul (85+)</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>SKP Dosen Menunggu</h6>
                            <h3><?= count($skpMenunggu) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">SKP Kaprodi <?= date('Y') ?></h6>
                    <?php if(empty($skpKaprodi)): ?>
                    <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary-custom btn-sm">Buat SKP</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($skpKaprodi)): ?>
                    <?php foreach($skpKaprodi as $skp): ?>
                    <div class="d-flex justify-content-between p-2 border rounded mb-2">
                        <div>
                            <strong><?= $periodeAktif['nama_periode'] ?? '-' ?></strong><br>
                            <small>Status: <?= $skp['status'] ?></small>
                        </div>
                        <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                            class="btn btn-sm btn-outline-primary">Detail</a>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center">Belum ada SKP</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>