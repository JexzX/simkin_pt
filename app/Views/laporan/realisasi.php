<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Realisasi - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'laporan_realisasi']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Laporan Realisasi</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="get" class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Periode</label>
                                <select name="periode_id" class="form-select">
                                    <option value="">-- Semua Periode --</option>
                                    <?php foreach($periodeList as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($periodeId == $p['id']) ? 'selected' : '' ?>><?= $p['nama_periode'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Bulan</label>
                                <select name="bulan" class="form-select">
                                    <option value="">-- Semua Bulan --</option>
                                    <?php for($i=1; $i<=12; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($bulan == $i) ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$i,1)) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" name="bawahan_saya" value="1" id="bawahanCheck" <?= ($filterBawahan ?? false) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="bawahanCheck">Bawahan Saya</label>
                                </div>
                                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                                <a href="<?= base_url('/laporan/export/realisasi?periode_id=' . $periodeId) ?>" class="btn btn-success"><i class="fas fa-download me-1"></i> Export CSV</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="50">No</th>
                                        <th>Nama</th>
                                        <th>Unit Kerja</th>
                                        <th>RHK</th>
                                        <th>Bulan</th>
                                        <th class="text-center">Realisasi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($realisasiList as $real): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($real['nama_lengkap']) ?></td>
                                        <td><?= esc($real['unit_kerja']) ?></td>
                                        <td><?= esc($real['nama_rhk']) ?></td>
                                        <td><?= date('F', mktime(0,0,0,$real['bulan'],1)) ?></td>
                                        <td class="text-center"><?= $real['realisasi_kuantitas'] ?? $real['realisasi_kualitas'] ?? '-' ?></td>
                                        <td class="text-center">
                                            <?php
                                            $statusClass = match($real['status']) {
                                                'disetujui' => 'success',
                                                'ditolak' => 'danger',
                                                'menunggu_approval' => 'warning',
                                                'draft' => 'secondary',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $real['status'])) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if(!empty($real['skp_id'])): ?>
                                            <a href="<?= base_url('/skp/detail/' . $real['skp_id']) ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye me-1"></i> Detail</a>
                                            <?php else: ?>
                                            -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($realisasiList)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td>
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
