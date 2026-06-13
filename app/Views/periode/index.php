<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periode - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'periode']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Manajemen Periode</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <a href="<?= base_url('/periode/create') ?>" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Periode</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Periode</th>
                                        <th>Tahun</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Batas SKP</th>
                                        <th>Batas Realisasi</th>
                                        <th>Batas Penilaian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($periodeList as $p): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $p['nama_periode'] ?></td>
                                        <td><?= $p['tahun'] ?></td>
                                        <td><?= $p['tanggal_mulai'] ?></td>
                                        <td><?= $p['tanggal_selesai'] ?></td>
                                        <td><?= $p['batas_akhir_pengajuan_skp'] ?? '-' ?></td>
                                        <td><?= $p['batas_akhir_realisasi'] ?? '-' ?></td>
                                        <td><?= $p['batas_akhir_penilaian'] ?? '-' ?></td>
                                        <td>
                                            <?php if($p['is_active']): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('/periode/toggle-active/' . $p['id']) ?>" class="btn btn-sm btn-<?= $p['is_active'] ? 'warning' : 'success' ?>">
                                                <?= $p['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                                            </a>
                                            <a href="<?= base_url('/periode/edit/' . $p['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="<?= base_url('/periode/delete/' . $p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus periode?')">Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($periodeList)): ?>
                                    <tr>
                                        <td colspan="10" class="text-center">Belum ada periode. <a href="<?= base_url('/periode/create') ?>">Buat periode baru</a></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
