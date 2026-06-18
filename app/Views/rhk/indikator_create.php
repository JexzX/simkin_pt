<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Indikator - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah Indikator</h5>
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
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fa-lg"></i>
                            <div>
                                <strong>RHK:</strong> <?= esc($rhk['nama_rhk'] ?? '') ?><br>
                                <strong>SKP Periode:</strong> <?= esc($skp['nama_periode'] ?? '-') ?>
                            </div>
                        </div>

                        <form action="<?= base_url('/rhk/indikator/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="rhk_id" value="<?= $rhk['id'] ?>">

                            <?php if($isRektor): ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Indikator <span class="text-danger">*</span></label>
                                <textarea name="indikator_manual" class="form-control" rows="3" required placeholder="Contoh: Terciptanya peningkatan mutu pendidikan tinggi yang berdaya saing"></textarea>
                                <small class="text-muted">Tuliskan indikator secara naratif</small>
                            </div>
                            <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Aspek <span class="text-danger">*</span></label>
                                <select name="aspek" class="form-select" required>
                                    <option value="">-- Pilih Aspek --</option>
                                    <option value="Kualitas">Kualitas</option>
                                    <option value="Kuantitas">Kuantitas</option>
                                    <option value="Waktu">Waktu</option>
                                    <option value="Biaya">Biaya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Indikator Kinerja Individu <span class="text-danger">*</span></label>
                                <textarea name="indikator" class="form-control" rows="2" required placeholder="Contoh: Jumlah laporan yang diselesaikan tepat waktu"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Target Tahunan <span class="text-danger">*</span></label>
                                <input type="text" name="target" class="form-control" required placeholder="Contoh: 12 laporan/tahun">
                            </div>
                            <?php endif; ?>

                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Indikator</button>
                            <a href="<?= base_url('/skp/detail/' . $rhk['skp_id']) ?>" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
