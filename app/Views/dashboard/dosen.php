<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'dashboard']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Dosen</h5>
            <div class="dropdown">
                <a href="#" class="text-dark text-decoration-none" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg me-1"></i> <?= session()->get('nama_lengkap') ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="p-4">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">SKP Dosen <?= date('Y') ?></h6>
                    <?php if(empty($skpDosen)): ?>
                    <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary-custom btn-sm">Buat SKP</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($skpDosen)): ?>
                    <?php foreach($skpDosen as $skp): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                        <div>
                            <strong><?= $periodeAktif['nama_periode'] ?? '-' ?></strong><br>
                            <small>Status: <?= $skp['status'] ?></small>
                        </div>
                        <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                            class="btn btn-sm btn-outline-primary">Detail</a>
                    </div>
                    <?php endforeach; ?>

                    <!-- Progress -->
                    <h6 class="mt-3">Progress RHK</h6>
                    <?php foreach($progress as $p): ?>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small">
                            <span><?= $p['nama_rhk'] ?></span>
                            <span><?= $p['persen'] ?>% (<?= $p['realisasi'] ?>/<?= $p['target'] ?>)</span>
                        </div>
                        <div class="progress progress-custom">
                            <div class="progress-bar bg-success" style="width: <?= $p['persen'] ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada SKP. Klik "Buat SKP" untuk memulai.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Jangan lupa input realisasi setiap bulan melalui menu <strong>Realisasi</strong>.
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>