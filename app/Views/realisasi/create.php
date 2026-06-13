<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Realisasi - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'realisasi']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> <?= $existing ? 'Edit Realisasi' : 'Input Realisasi' ?></h5>
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
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">RHK</h6>
                                <p class="fw-semibold mb-0"><?= $rhk['nama_rhk'] ?></p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Indikator</h6>
                                <p class="fw-semibold mb-0"><?= $indikator['indikator'] ?></p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Target</h6>
                                <p class="fw-semibold mb-0"><?= $indikator['target'] ?></p>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Periode</h6>
                                <p class="fw-semibold mb-0"><?= date('F', mktime(0,0,0,$bulan,1)) ?> <?= date('Y') ?></p>
                            </div>
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">Perspektif</h6>
                                <p class="fw-semibold mb-0"><?= $indikator['perspektif'] ?? ($rhk['jenis_rhk'] == 'kuantitatif' ? 'KUANTITAS' : 'KUALITAS') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url('/realisasi/store') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="rhk_indikator_id" value="<?= $indikator['id'] ?>">
                            <input type="hidden" name="bulan" value="<?= $bulan ?>">
                            <input type="hidden" name="tahun" value="<?= date('Y') ?>">

                            <div class="row g-3">
                                <?php $perspektif = strtoupper($indikator['perspektif'] ?? ($rhk['jenis_rhk'] == 'kuantitatif' ? 'KUANTITAS' : 'KUALITAS')); ?>
                                <?php if($perspektif == 'KUANTITAS'): ?>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Realisasi Kuantitas <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="realisasi_kuantitas" class="form-control" value="<?= $existing['realisasi_kuantitas'] ?? '' ?>" step="0.01" required>
                                            <span class="input-group-text"><?= $rhk['target_satuan'] ?? 'Unit' ?></span>
                                        </div>
                                        <small class="text-muted">Target: <?= $indikator['target'] ?> <?= $rhk['target_satuan'] ?? '' ?></small>
                                    </div>
                                </div>
                                <?php elseif($perspektif == 'KUALITAS'): ?>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Realisasi Kualitas <span class="text-danger">*</span></label>
                                        <textarea name="realisasi_kualitas" class="form-control" rows="4" required><?= $existing['realisasi_kualitas'] ?? '' ?></textarea>
                                        <small class="text-muted">Uraian hasil kerja secara kualitas</small>
                                    </div>
                                </div>
                                <?php elseif($perspektif == 'WAKTU'): ?>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Realisasi Waktu <span class="text-danger">*</span></label>
                                        <input type="date" name="realisasi_waktu" class="form-control" value="<?= $existing['realisasi_waktu'] ?? date('Y-m-d') ?>" required>
                                        <small class="text-muted">Tanggal pelaksanaan kegiatan</small>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Bukti File</label>
                                        <input type="file" name="bukti_file" class="form-control">
                                        <small class="text-muted">PDF, JPG, PNG (max 5MB)</small>
                                        <?php if(!empty($existing['bukti_file'])): ?>
                                        <div class="mt-2">
                                            <a href="<?= base_url($existing['bukti_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file me-1"></i>Lihat File</a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)"><?= $existing['catatan'] ?? '' ?></textarea>
                            </div>

                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                                <a href="<?= base_url('/realisasi?bulan=' . $bulan) ?>" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Batal</a>
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
