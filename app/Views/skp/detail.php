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
                                <small class="text-muted">Jumlah RHK</small>
                                <p class="fw-semibold mb-0"><?= count($rhkList) ?> RHK</p>
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
                    <?php if(!empty($rhkList) && $semuaPunyaIndikator): ?>
                    <form action="<?= base_url('/skp/submit/' . $skp['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Ajukan SKP ini ke atasan?')"><i class="fas fa-paper-plane me-2"></i>Ajukan SKP</button>
                    </form>
                    <?php else: ?>
                    <button class="btn btn-secondary" disabled title="Lengkapi RHK dan indikator terlebih dahulu"><i class="fas fa-exclamation-triangle me-2"></i>Ajukan SKP</button>
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
                                        <th style="min-width:220px">RHK Pimpinan yang Diintervensi</th>
                                        <th style="min-width:220px">Rencana Hasil Kerja</th>
                                        <th style="width:100px">Aspek</th>
                                        <th style="min-width:220px">Indikator Kinerja Individu</th>
                                        <th style="min-width:150px">Target Tahunan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($rhkList as $rhk):
                                        $jml = count($rhk['indikator']);
                                        $rowspan = max($jml, 1) + 1; // +1 untuk baris Tambah Indikator
                                    ?>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="<?= $rowspan ?>">
                                            <?= $no++ ?>
                                        </td>
                                        <td class="align-middle" rowspan="<?= $rowspan ?>">
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                <small class="fw-semibold"><?= esc($rhk['intervensi_dari_nama'] ?? '-') ?></small>
                                                <?php if (!empty($rhk['intervensi_indikator_id']) && !empty($rhk['intervensi_dari_data'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-1" data-bs-toggle="modal" data-bs-target="#infoAtasanModal<?= $rhk['id'] ?>" title="Info RHK Atasan"><i class="fas fa-info-circle"></i></button>
                                                <?php endif; ?>
                                                <?php if(!empty($rhk['intervensi_dari_terpilih'])): ?>
                                                <br><small class="text-muted">Indikator diintervensi: <?= count($rhk['intervensi_dari_terpilih']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="align-middle" rowspan="<?= $rowspan ?>">
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                <span class="fw-semibold"><?= esc($rhk['nama_rhk']) ?></span>
                                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-1" data-bs-toggle="modal" data-bs-target="#infoRhkModal<?= $rhk['id'] ?>" title="Info RHK"><i class="fas fa-info-circle"></i></button>
                                                <?php if($skp['status'] == 'draft'): ?>
                                                <a href="<?= base_url('/rhk/edit/' . $rhk['id']) ?>" class="btn btn-sm btn-outline-warning py-0 px-1" title="Edit RHK"><i class="fas fa-edit"></i></a>
                                                <a href="<?= base_url('/rhk/delete/' . $rhk['id']) ?>" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="return confirm('Hapus RHK ini?')" title="Hapus RHK"><i class="fas fa-trash"></i></a>
                                                <?php endif; ?>
                                            </div>
                                            <br><small class="text-muted">(<?= ucfirst($rhk['klasifikasi']) ?>)</small>
                                        </td>

                                        <?php if($jml > 0): $first = true; foreach($rhk['indikator'] as $ind): ?>
                                        <?php if(!$first): ?>
                                    </tr><tr>
                                        <?php endif; ?>
                                        <td class="text-center align-middle"><span class="badge bg-info"><?= esc($ind['aspek'] ?? '-') ?></span></td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                <span><?= esc($ind['indikator']) ?></span>
                                                <?php if($skp['status'] == 'draft'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-1" data-bs-toggle="modal" data-bs-target="#infoIndikatorModal<?= $ind['id'] ?>" title="Info Indikator"><i class="fas fa-info-circle"></i></button>
                                                <a href="<?= base_url('/rhk/indikator/edit/' . $ind['id']) ?>" class="btn btn-sm btn-outline-warning py-0 px-1" title="Edit Indikator"><i class="fas fa-edit"></i></a>
                                                <a href="<?= base_url('/rhk/indikator/delete/' . $ind['id']) ?>" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="return confirm('Hapus indikator ini?')" title="Hapus Indikator"><i class="fas fa-trash"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="align-middle"><?= esc($ind['target'] ?? '-') ?></td>
                                        <?php $first = false; endforeach; ?>
                                        <tr class="table-light">
                                            <td colspan="3" class="text-center py-2">
                                                <?php if($skp['status'] == 'draft'): ?>
                                                <a href="<?= base_url('/rhk/indikator/create/' . $rhk['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1"></i>Tambah Indikator</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                        <td class="text-muted align-middle text-center">-</td>
                                        <td class="text-muted align-middle text-center">-</td>
                                        <td class="text-muted align-middle text-center">-</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-center py-2">
                                            <?php if($skp['status'] == 'draft'): ?>
                                            <a href="<?= base_url('/rhk/indikator/create/' . $rhk['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1"></i>Tambah Indikator</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                        <?php endif; ?>
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

    <?php foreach($rhkList as $rhk): ?>

    <?php if(!empty($rhk['intervensi_dari_data'])): ?>
    <div class="modal fade" id="infoAtasanModal<?= $rhk['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail RHK Pimpinan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th style="width:120px">RHK</th><td><?= esc($rhk['intervensi_dari_data']['nama_rhk'] ?? '-') ?></td></tr>
                        <tr><th>Klasifikasi</th><td><?= ucfirst($rhk['intervensi_dari_data']['klasifikasi'] ?? '-') ?></td></tr>
                    </table>
                    <?php if(!empty($rhk['intervensi_dari_indikator'])): ?>
                    <hr>
                    <h6>Indikator Tersedia:</h6>
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Aspek</th><th>Indikator</th><th>Target</th></tr></thead>
                        <tbody>
                        <?php foreach($rhk['intervensi_dari_indikator'] as $i): ?>
                        <tr><td><?= esc($i['aspek']) ?></td><td><?= esc($i['indikator']) ?></td><td><?= esc($i['target'] ?? '-') ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                    <?php if(!empty($rhk['intervensi_dari_terpilih'])): ?>
                    <hr>
                    <h6>Indikator yang Diintervensi:</h6>
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Aspek</th><th>Indikator</th><th>Target</th></tr></thead>
                        <tbody>
                        <?php foreach($rhk['intervensi_dari_terpilih'] as $i): ?>
                        <tr><td><?= esc($i['aspek'] ?? '-') ?></td><td><?= esc($i['indikator']) ?></td><td><?= esc($i['target'] ?? '-') ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="modal fade" id="infoRhkModal<?= $rhk['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail RHK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th style="width:120px">RHK</th><td><?= esc($rhk['nama_rhk']) ?></td></tr>
                        <tr><th>Klasifikasi</th><td><?= ucfirst($rhk['klasifikasi']) ?></td></tr>
                        <?php if(!empty($rhk['intervensi_dari_nama']) && $rhk['intervensi_dari_nama'] != '-'): ?>
                        <tr><th>Intervensi dari</th><td><?= esc($rhk['intervensi_dari_nama']) ?></td></tr>
                        <?php endif; ?>
                    </table>
                    <?php if(!empty($rhk['indikator'])): ?>
                    <hr>
                    <h6>Indikator:</h6>
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Aspek</th><th>Indikator</th><th>Target</th></tr></thead>
                        <tbody>
                        <?php foreach($rhk['indikator'] as $i): ?>
                        <tr><td><?= esc($i['aspek']) ?></td><td><?= esc($i['indikator']) ?></td><td><?= esc($i['target'] ?? '-') ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php foreach($rhk['indikator'] as $ind): ?>
    <div class="modal fade" id="infoIndikatorModal<?= $ind['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Indikator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th style="width:120px">Aspek</th><td><?= esc($ind['aspek'] ?? '-') ?></td></tr>
                        <tr><th>Indikator</th><td><?= esc($ind['indikator']) ?></td></tr>
                        <tr><th>Target</th><td><?= esc($ind['target'] ?? '-') ?></td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
