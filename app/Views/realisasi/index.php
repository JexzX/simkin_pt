<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realisasi - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'realisasi']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Realisasi Kinerja</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>

                <?php if(!$skp): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size:3rem"></i>
                        <h5 class="text-muted">SKP Belum Disetujui</h5>
                        <p class="text-muted mb-3">Anda belum memiliki SKP yang disetujui. Silakan buat dan ajukan SKP terlebih dahulu.</p>
                        <a href="<?= base_url('/skp') ?>" class="btn btn-primary"><i class="fas fa-file-alt me-2"></i>Buat SKP</a>
                    </div>
                </div>
                <?php else: ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h6 class="text-muted mb-1">Periode</h6>
                                <span class="fw-semibold"><?= $periode['nama_periode'] ?? 'Periode Aktif' ?></span>
                                <span class="badge bg-success ms-2"><?= $skp['status'] ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="<?= base_url('/realisasi?bulan=' . ($bulanAktif > 1 ? $bulanAktif - 1 : 12)) ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
                                <form method="get" class="mb-0">
                                    <select name="bulan" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                                        <?php for($i=1; $i<=12; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($bulanAktif == $i) ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$i,1)) ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                                <a href="<?= base_url('/realisasi?bulan=' . ($bulanAktif < 12 ? $bulanAktif + 1 : 1)) ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px">No</th>
                                        <th>RHK</th>
                                        <th>Indikator</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($rhkList as $rhk): ?>
                                    <?php if(!empty($rhk['indikator'])): ?>
                                        <?php $first = true; foreach($rhk['indikator'] as $ind): ?>
                                        <?php
                                            $real = null;
                                            foreach($realisasiList as $r) {
                                                if($r['rhk_indikator_id'] == $ind['id']) {
                                                    $real = $r;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= $first ? $rhk['nama_rhk'] : '' ?></td>
                                            <td><?= $ind['indikator'] ?></td>
                                            <td><?= $ind['target'] ?></td>
                                            <td>
                                                <?php if($real): ?>
                                                    <?php if($real['realisasi_kuantitas'] !== null): ?>
                                                        <span class="fw-semibold"><?= $real['realisasi_kuantitas'] ?></span>
                                                    <?php elseif($real['realisasi_kualitas']): ?>
                                                        <span class="fw-semibold"><?= esc(substr($real['realisasi_kualitas'], 0, 80)) ?><?= strlen($real['realisasi_kualitas']) > 80 ? '...' : '' ?></span>
                                                    <?php elseif($real['realisasi_waktu']): ?>
                                                        <span class="fw-semibold"><?= date('d/m/Y', strtotime($real['realisasi_waktu'])) ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($real): ?>
                                                    <?php $badgeMap = ['draft'=>'secondary', 'menunggu_approval'=>'warning', 'disetujui'=>'success', 'ditolak'=>'danger']; ?>
                                                    <span class="badge bg-<?= $badgeMap[$real['status']] ?? 'secondary' ?> badge-status"><?= ucfirst(str_replace('_', ' ', $real['status'])) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary badge-status">Belum</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('/realisasi/create/' . $ind['id'] . '?bulan=' . $bulanAktif) ?>" class="btn btn-sm <?= $real ? 'btn-warning' : 'btn-primary' ?>">
                                                    <i class="fas <?= $real ? 'fa-edit' : 'fa-plus' ?>"></i> <?= $real ? 'Edit' : 'Input' ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $first = false; endforeach; ?>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if(empty($rhkList)): ?>
                                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada RHK</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
