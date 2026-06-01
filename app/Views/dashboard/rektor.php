<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'dashboard']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Rektor</h5>
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
            <!-- Target Utama -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i> Target Akreditasi Prodi
                                Unggul</h6>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="mb-3"><?= $realisasiAkreditasi ?>% / <?= $targetAkreditasi ?>%</h2>
                            <div class="progress progress-custom mb-3">
                                <div class="progress-bar bg-success"
                                    style="width: <?= ($realisasiAkreditasi / $targetAkreditasi) * 100 ?>%"></div>
                            </div>
                            <p class="text-muted small">Target: <?= $targetAkreditasi ?>% prodi akreditasi unggul</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-graduation-cap me-2 text-primary"></i> Target Peningkatan
                                Mahasiswa</h6>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="mb-3">3% / 5%</h2>
                            <div class="progress progress-custom mb-3">
                                <div class="progress-bar bg-info" style="width: 60%"></div>
                            </div>
                            <p class="text-muted small">Target: 5% peningkatan jumlah mahasiswa</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKP Pribadi -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i> SKP Rektor <?= date('Y') ?></h6>
                    <?php if(empty($skpRektor)): ?>
                    <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary-custom btn-sm">
                        <i class="fas fa-plus me-1"></i> Buat SKP
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($skpRektor)): ?>
                    <?php foreach($skpRektor as $skp): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <strong>Periode: <?= $periodeAktif['nama_periode'] ?? '-' ?></strong><br>
                            <small class="text-muted">Status:
                                <span
                                    class="badge bg-<?= $skp['status'] == 'disetujui' ? 'success' : ($skp['status'] == 'menunggu_approval' ? 'warning' : 'secondary') ?>">
                                    <?= $skp['status'] ?>
                                </span>
                            </small>
                        </div>
                        <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada SKP untuk periode ini</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SKP Dekan Menunggu Persetujuan -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i> SKP Dekan Menunggu Persetujuan</h6>
                </div>
                <div class="card-body">
                    <?php if(!empty($skpMenunggu)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Dekan</th>
                                    <th>Unit Kerja</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($skpMenunggu as $skp): ?>
                                <tr>
                                    <td><?= $skp['user_name'] ?? '-' ?></td>
                                    <td><?= $skp['unit_kerja'] ?? '-' ?></td>
                                    <td><?= date('d/m/Y', strtotime($skp['tanggal_pengajuan'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-check"></i> Review
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Tidak ada SKP yang menunggu persetujuan</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>