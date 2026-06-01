<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail SKP - SIMKIN UIN Salatiga</title>
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

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
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
            <nav class="navbar-top d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Detail SKP</h5>
                <a href="<?= base_url('/skp') ?>" class="btn btn-secondary btn-sm">Kembali</a>
            </nav>

            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <!-- Info SKP -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Informasi SKP</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="150">ID SKP</td>
                                        <td>: <?= $skp['id'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Periode</td>
                                        <td>: <?= $skp['periode_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:
                                            <span
                                                class="badge badge-status bg-<?= $skp['status'] == 'disetujui' ? 'success' : ($skp['status'] == 'menunggu_approval' ? 'warning' : 'secondary') ?>">
                                                <?= $skp['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="150">Total Bobot</td>
                                        <td>: <?= $totalBobot ?>%</td>
                                    </tr>
                                    <?php if($skp['tanggal_pengajuan']): ?>
                                    <tr>
                                        <td>Tgl Pengajuan</td>
                                        <td>: <?= date('d/m/Y H:i', strtotime($skp['tanggal_pengajuan'])) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        <?php if($skp['catatan_atasan']): ?>
                        <div class="alert alert-warning mt-2">
                            <strong>Catatan Atasan:</strong> <?= $skp['catatan_atasan'] ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <?php if($skp['status'] == 'draft'): ?>
                <div class="mb-3">
                    <?php if($bisaDiajukan): ?>
                    <form action="<?= base_url('/skp/submit/' . $skp['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Ajukan SKP ini ke atasan?')">
                            <i class="fas fa-paper-plane me-1"></i> Ajukan SKP
                        </button>
                    </form>
                    <?php else: ?>
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-exclamation-triangle me-1"></i> Total Bobot Harus 100% (Saat ini:
                        <?= $totalBobot ?>%)
                    </button>
                    <?php endif; ?>

                    <a href="<?= base_url('/skp/delete/' . $skp['id']) ?>" class="btn btn-danger"
                        onclick="return confirm('Hapus SKP ini?')">
                        <i class="fas fa-trash me-1"></i> Hapus SKP
                    </a>
                </div>
                <?php endif; ?>

                <!-- Daftar RHK -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i> Daftar RHK</h6>
                        <?php if($skp['status'] == 'draft'): ?>
                        <a href="<?= base_url('/rhk/create/' . $skp['id']) ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah RHK
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if(empty($rhkList)): ?>
                        <p class="text-muted text-center mb-0">Belum ada RHK. Silakan tambah RHK terlebih dahulu.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama RHK</th>
                                        <th>Jenis</th>
                                        <th>Target</th>
                                        <th>Bobot</th>
                                        <th>Indikator</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($rhkList as $rhk): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $rhk['nama_rhk'] ?></td>
                                        <td><?= $rhk['jenis_rhk'] ?> / <?= $rhk['klasifikasi'] ?></td>
                                        <td>
                                            <?php if($rhk['jenis_rhk'] == 'kuantitatif'): ?>
                                            <?= $rhk['target_kuantitas'] ?> <?= $rhk['target_satuan'] ?>
                                            <?php else: ?>
                                            <?= substr($rhk['target_kualitas'], 0, 50) ?>...
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $rhk['bobot'] ?>%</td>
                                        <td>
                                            <?php foreach($rhk['indikator'] as $ind): ?>
                                            <div class="small">• <?= $ind['indikator'] ?> (Target:
                                                <?= $ind['target'] ?>)</div>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php if($skp['status'] == 'draft'): ?>
                                            <a href="<?= base_url('/rhk/edit/' . $rhk['id']) ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('/rhk/delete/' . $rhk['id']) ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus RHK ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>