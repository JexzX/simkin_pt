<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian SKP - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'penilaian']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i> <?= $existing ? 'Edit Penilaian' : 'Penilaian SKP' ?></h5>
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
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Pegawai</h6>
                                <p class="fw-semibold mb-0"><?= $skp['user_name'] ?></p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Unit Kerja</h6>
                                <p class="fw-semibold mb-0"><?= $skp['unit_kerja'] ?></p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">Periode</h6>
                                <p class="fw-semibold mb-0"><?= $skp['nama_periode'] ?> (<?= $skp['tahun'] ?>)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(!empty($rhkList)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-list-check me-2"></i>Daftar RHK & Realisasi</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px">No</th>
                                        <th>RHK</th>
                                        <th>Bobot</th>
                                        <th>Target</th>
                                        <th>Progress Realisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($rhkList as $rhk): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $rhk['nama_rhk'] ?></td>
                                        <td><?= $rhk['bobot'] ?>%</td>
                                        <td><?= $rhk['target_kuantitas'] ?> <?= $rhk['target_satuan'] ?? '' ?></td>
                                        <td>
                                            <?php if(isset($progress[$rhk['id']])): ?>
                                                <?php $p = $progress[$rhk['id']]; ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height:8px">
                                                        <div class="progress-bar <?= $p['persen'] >= 100 ? 'bg-success' : ($p['persen'] >= 50 ? 'bg-info' : 'bg-warning') ?>" style="width:<?= $p['persen'] ?>%"></div>
                                                    </div>
                                                    <small class="fw-semibold"><?= $p['persen'] ?>%</small>
                                                </div>
                                                <small class="text-muted"><?= $p['realisasi'] ?> / <?= $p['target'] ?></small>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic">Belum ada realisasi</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Form Penilaian</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('/penilaian/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="skp_id" value="<?= $skp['id'] ?>">

                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <span class="badge bg-primary" style="font-size:0.7rem">Bobot 50%</span>
                                            </div>
                                            <label class="form-label fw-semibold">Nilai Kuantitas</label>
                                            <input type="number" name="nilai_kuantitas" class="form-control text-center form-control-lg" min="0" max="100" required value="<?= $existing['nilai_kuantitas'] ?? '' ?>">
                                            <small class="text-muted">Rentang 0 - 100</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <span class="badge bg-info" style="font-size:0.7rem">Bobot 30%</span>
                                            </div>
                                            <label class="form-label fw-semibold">Nilai Kualitas</label>
                                            <input type="number" name="nilai_kualitas" class="form-control text-center form-control-lg" min="0" max="100" required value="<?= $existing['nilai_kualitas'] ?? '' ?>">
                                            <small class="text-muted">Rentang 0 - 100</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <span class="badge bg-secondary" style="font-size:0.7rem">Bobot 20%</span>
                                            </div>
                                            <label class="form-label fw-semibold">Nilai Waktu</label>
                                            <input type="number" name="nilai_waktu" class="form-control text-center form-control-lg" min="0" max="100" required value="<?= $existing['nilai_waktu'] ?? '' ?>">
                                            <small class="text-muted">Rentang 0 - 100</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Catatan Penilai</label>
                                <textarea name="catatan_penilai" class="form-control" rows="4" placeholder="Catatan penilaian (opsional)"><?= $existing['catatan_penilai'] ?? '' ?></textarea>
                            </div>

                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan penilaian ini?')"><i class="fas fa-save me-2"></i>Simpan Penilaian</button>
                                <a href="<?= base_url('/penilaian') ?>" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
