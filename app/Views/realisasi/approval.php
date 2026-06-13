<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Realisasi - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'approval_realisasi']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i> Persetujuan Realisasi</h5>
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px">No</th>
                                        <th>Nama Pegawai</th>
                                        <th>RHK</th>
                                        <th>Indikator</th>
                                        <th>Bulan</th>
                                        <th>Realisasi</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($realisasiList as $real): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><span class="fw-semibold"><?= $real['user_name'] ?></span><br><small class="text-muted"><?= $real['unit_kerja'] ?? '-' ?></small></td>
                                        <td><?= $real['nama_rhk'] ?></td>
                                        <td><?= $real['indikator'] ?></td>
                                        <td><?= date('F', mktime(0,0,0,$real['bulan'],1)) ?></td>
                                        <td>
                                            <?php if($real['realisasi_kuantitas'] !== null): ?>
                                                <span class="fw-semibold"><?= $real['realisasi_kuantitas'] ?></span>
                                            <?php elseif($real['realisasi_kualitas']): ?>
                                                <?= esc(substr($real['realisasi_kualitas'], 0, 60)) ?>
                                            <?php elseif($real['realisasi_waktu']): ?>
                                                <?= date('d/m/Y', strtotime($real['realisasi_waktu'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $real['catatan'] ? esc($real['catatan']) : '<span class="text-muted">-</span>' ?></td>
                                        <td><span class="badge bg-warning badge-status">Menunggu</span></td>
                                        <td class="text-center">
                                            <form action="<?= base_url('/realisasi/approve/' . $real['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui realisasi ini?')"><i class="fas fa-check"></i></button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $real['id'] ?>"><i class="fas fa-times"></i></button>

                                            <div class="modal fade" id="rejectModal<?= $real['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Realisasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?= base_url('/realisasi/reject/' . $real['id']) ?>" method="post">
                                                            <?= csrf_field() ?>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Pegawai</label>
                                                                    <p class="mb-0"><?= $real['user_name'] ?> - <?= $real['nama_rhk'] ?></p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                    <textarea name="catatan" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger"><i class="fas fa-times me-2"></i>Tolak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($realisasiList)): ?>
                                    <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada realisasi yang menunggu persetujuan</td></tr>
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
