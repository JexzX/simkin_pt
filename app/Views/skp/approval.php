<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan SKP - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'approval_skp']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-check-double me-2"></i> Persetujuan SKP</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body p-0">
                        <?php if(empty($skpList)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada SKP yang menunggu persetujuan</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Unit Kerja</th>
                                        <th>Jabatan</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($skpList as $skp): ?>
                                    <tr>
                                        <td class="ps-4"><?= $no++ ?></td>
                                        <td><strong><?= esc($skp['user_name'] ?? $skp['nama_lengkap'] ?? '-') ?></strong></td>
                                        <td><?= esc($skp['unit_kerja'] ?? '-') ?></td>
                                        <td><?= esc($skp['jabatan'] ?? '-') ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($skp['tanggal_pengajuan'])) ?></td>
                                        <td class="text-end pe-4">
                                            <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Detail</a>
                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveModal<?= $skp['id'] ?>"><i class="fas fa-check"></i> Setujui</button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $skp['id'] ?>"><i class="fas fa-undo"></i> Revisi</button>
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

    <!-- Approve & Revisi Modals -->
    <?php foreach($skpList as $skp): ?>
    <div class="modal fade" id="approveModal<?= $skp['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('/approval/skp/approve/' . $skp['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Setujui SKP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Setujui SKP dari <strong><?= esc($skp['user_name'] ?? $skp['nama_lengkap'] ?? '-') ?></strong>?</p>
                        <div class="mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan persetujuan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal<?= $skp['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('/approval/skp/reject/' . $skp['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Minta Revisi SKP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Minta revisi SKP dari <strong><?= esc($skp['user_name'] ?? $skp['nama_lengkap'] ?? '-') ?></strong>?</p>
                        <div class="mb-3">
                            <label class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Jelaskan apa yang perlu direvisi..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-undo me-1"></i> Minta Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
