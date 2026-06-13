<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail SKP - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Detail SKP</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Periode</small>
                                <p class="fw-semibold mb-0"><?= esc($skp['nama_periode'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Tgl Mulai</small>
                                <p class="fw-semibold mb-0"><?= $skp['tanggal_mulai'] ?? '-' ?></p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Tgl Selesai</small>
                                <p class="fw-semibold mb-0"><?= $skp['tanggal_selesai'] ?? '-' ?></p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Pendekatan</small>
                                <p class="fw-semibold mb-0"><?= ucfirst($skp['pendekatan'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Status</small>
                                <div>
                                    <?php $badge = ['draft'=>'secondary','pengajuan'=>'warning','disetujui'=>'success','ditolak'=>'danger','selesai'=>'info']; ?>
                                    <span class="badge bg-<?= $badge[$skp['status']] ?? 'secondary' ?> badge-status">
                                        <?= ucfirst($skp['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Pegawai</small>
                                <p class="fw-semibold mb-0"><?= esc($skp['user_name'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Unit Kerja</small>
                                <p class="fw-semibold mb-0"><?= esc($skp['unit_kerja'] ?? '-') ?></p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Total Bobot</small>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height:8px;border-radius:10px;">
                                        <div class="progress-bar bg-success" style="width:<?= min($totalBobot,100) ?>%;border-radius:10px;"></div>
                                    </div>
                                    <strong><?= $totalBobot ?>%</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($skp['catatan_atasan']): ?>
                <div class="alert alert-info">
                    <strong>Catatan Atasan:</strong> <?= esc($skp['catatan_atasan']) ?>
                </div>
                <?php endif; ?>

                <div class="d-flex gap-2 mb-4">
                    <?php if($skp['status'] == 'draft' && $currentUserId == $skp['user_id']): ?>
                    <a href="<?= base_url('/rhk/create/' . $skp['id']) ?>" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah RHK</a>
                    <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>" class="btn btn-outline-secondary" onclick="window.print();return false;"><i class="fas fa-print me-2"></i>Cetak</a>
                    <?php
                    $semuaPunyaIndikator = true;
                    foreach ($rhkList as $rhk) {
                        if (empty($rhk['indikator'])) { $semuaPunyaIndikator = false; break; }
                    }
                    ?>
                    <?php if(!empty($rhkList) && $totalBobot == 100 && $semuaPunyaIndikator): ?>
                    <form action="<?= base_url('/skp/submit/' . $skp['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Ajukan SKP ini ke atasan?')"><i class="fas fa-paper-plane me-2"></i>Ajukan SKP</button>
                    </form>
                    <?php else: ?>
                    <button class="btn btn-secondary" disabled title="Lengkapi RHK, bobot 100%, dan indikator terlebih dahulu"><i class="fas fa-exclamation-triangle me-2"></i>Ajukan SKP</button>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if($skp['status'] == 'pengajuan' && $isAtasan && $currentUserId != $skp['user_id']): ?>
                    <form action="<?= base_url('/approval/skp/approve/' . $skp['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Setujui SKP ini?')"><i class="fas fa-check me-2"></i>Setujui</button>
                    </form>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#revisiModal"><i class="fas fa-undo me-2"></i>Revisi</button>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Hasil Kerja</h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if(empty($rhkList)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Belum ada RHK. Klik "Tambah RHK" untuk memulai.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px">No</th>
                                        <th style="min-width:200px">RHK Pimpinan yang Diintervensi</th>
                                        <th style="min-width:200px">Rencana Hasil Kerja</th>
                                        <th style="width:120px">Aspek</th>
                                        <th style="min-width:200px">Indikator Kinerja Individu</th>
                                        <th style="min-width:150px">Target Tahunan</th>
                                        <th class="text-center" style="width:140px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($rhkList as $rhk):
                                        $jmlIndikator = count($rhk['indikator']);
                                        $rowspan = max($jmlIndikator, 1);
                                    ?>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="<?= $rowspan ?>"><?= $no++ ?></td>
                                        <td class="align-middle" rowspan="<?= $rowspan ?>">
                                            <small><?= esc($rhk['intervensi_dari_nama'] ?? '-') ?></small>
                                            <?php if (!empty($rhk['intervensi_dari_id'])): ?>
                                            <br><span class="badge bg-light text-muted mt-1"><i class="fas fa-arrow-down"></i> Intervensi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle fw-semibold" rowspan="<?= $rowspan ?>">
                                            <?= esc($rhk['nama_rhk']) ?>
                                            <br><small class="text-muted">(<?= ucfirst($rhk['klasifikasi']) ?>) — Bobot: <?= $rhk['bobot'] ?>%</small>
                                        </td>
                                        <?php if($jmlIndikator > 0): $first = true; foreach($rhk['indikator'] as $ind): ?>
                                        <?php if(!$first): ?>
                                    </tr><tr>
                                        <?php endif; ?>
                                        <td><span class="badge bg-info"><?= esc($ind['aspek'] ?? '-') ?></span></td>
                                        <td><?= esc($ind['indikator']) ?></td>
                                        <td><?= esc($ind['target'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <?php if($skp['status'] == 'draft'): ?>
                                            <a href="<?= base_url('/rhk/indikator/delete/' . $ind['id']) ?>" class="text-danger" onclick="return confirm('Hapus indikator ini?')"><i class="fas fa-times"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <?php $first = false; endforeach; ?>
                                        <?php else: ?>
                                        <td class="text-muted align-middle">-</td>
                                        <td class="text-muted align-middle">-</td>
                                        <td class="text-muted align-middle">-</td>
                                        <td class="text-center align-middle">
                                            <?php if($skp['status'] == 'draft'): ?>
                                            <a href="<?= base_url('/rhk/indikator/create/' . $rhk['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1"></i>Indikator</a>
                                            <?php endif; ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('/skp') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="revisiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('/approval/skp/reject/' . $skp['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Minta Revisi SKP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Catatan Revisi</label>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
