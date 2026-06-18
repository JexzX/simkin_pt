<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SKP - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Daftar SKP</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="badge bg-info badge-status"><i class="fas fa-calendar-alt me-1"></i> Semua Periode</span>
                    </div>
                    <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Buat SKP Baru</a>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <?php if(empty($skpList)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Belum ada SKP</p>
                            <?php if(!$hasSkpAktif): ?>
                            <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Buat SKP Baru</a>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">No</th>
                                        <th>Periode</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Selesai</th>
                                        <th>Pendekatan</th>
                                        <th>Status</th>
                                        <th>Tgl Pengajuan</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($skpList as $skp): ?>
                                    <tr>
                                        <td class="ps-4"><?= $no++ ?></td>
                                        <td><?= esc($skp['periode_id'] ?? '-') ?></td>
                                        <td><?= !empty($skp['tanggal_mulai']) ? $skp['tanggal_mulai'] : '-' ?></td>
                                        <td><?= !empty($skp['tanggal_selesai']) ? $skp['tanggal_selesai'] : '-' ?></td>
                                        <td><span class="badge bg-light text-dark"><?= ucfirst($skp['pendekatan'] ?? '-') ?></span></td>
                                        <td>
                                            <?php $badge = ['draft'=>'secondary','pengajuan'=>'warning','disetujui'=>'success','ditolak'=>'danger','selesai'=>'info']; ?>
                                            <span class="badge bg-<?= $badge[$skp['status']] ?? 'secondary' ?> badge-status">
                                                <?php
                                                $label = [
                                                    'draft' => 'Draft',
                                                    'pengajuan' => 'Pengajuan',
                                                    'disetujui' => 'Disetujui',
                                                    'ditolak' => 'Ditolak',
                                                    'selesai' => 'Selesai'
                                                ];
                                                echo $label[$skp['status']] ?? ucfirst($skp['status']);
                                                ?>
                                            </span>
                                        </td>
                                        <td><?= !empty($skp['tanggal_pengajuan']) ? date('d/m/Y H:i', strtotime($skp['tanggal_pengajuan'])) : '-' ?></td>
                                        <td class="text-end pe-4">
                                            <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Detail</a>
                                            <?php if($skp['status'] == 'draft'): ?>
                                            <a href="<?= base_url('/skp/edit/' . $skp['id']) ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="<?= base_url('/skp/delete/' . $skp['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus SKP ini?')"><i class="fas fa-trash"></i> Hapus</a>
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
