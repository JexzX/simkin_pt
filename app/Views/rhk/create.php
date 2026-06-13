<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah RHK - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah RHK</h5>
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
                            <div>Sisa bobot yang tersedia: <strong><?= $sisaBobot ?>%</strong></div>
                        </div>

                        <form action="<?= base_url('/rhk/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="skp_id" value="<?= $skp_id ?>">

                            <?php if(!empty($intervensiList)): ?>
                            <div class="mb-4 p-3 bg-light rounded">
                                <label class="form-label fw-semibold"><i class="fas fa-arrow-down me-1"></i> RHK Atasan yang Diintervensi</label>
                                <select name="intervensi_dari_id" class="form-select">
                                    <option value="">-- Pilih RHK Atasan (opsional) --</option>
                                    <?php foreach($intervensiList as $intervensi): ?>
                                    <option value="<?= $intervensi['id'] ?>">
                                        <?= esc($intervensi['nama_rhk']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Pilih RHK atasan yang akan diintervensi/diambil sebagai acuan</small>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis RHK <span class="text-danger">*</span></label>
                                <select name="klasifikasi" class="form-select" required>
                                    <option value="">-- Pilih Jenis RHK --</option>
                                    <option value="utama">Utama</option>
                                    <option value="tambahan">Tambahan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Hasil Kerja <span class="text-danger">*</span></label>
                                <textarea name="nama_rhk" class="form-control" rows="3" required placeholder="Masukkan rencana hasil kerja"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bobot (%) <span class="text-danger">*</span></label>
                                <input type="number" name="bobot" class="form-control" min="0" max="<?= $sisaBobot ?>" step="5" value="0" required>
                                <small class="text-muted">Sisa bobot: <?= $sisaBobot ?>%</small>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan RHK</button>
                            <a href="<?= base_url('/skp/detail/' . $skp_id) ?>" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
