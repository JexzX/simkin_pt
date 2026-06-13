<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit RHK - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit RHK</h5>
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
                        <form action="<?= base_url('/rhk/update/' . $rhk['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="skp_id" value="<?= $skp_id ?>">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis RHK <span class="text-danger">*</span></label>
                                <select name="klasifikasi" class="form-select" required>
                                    <option value="utama" <?= $rhk['klasifikasi'] == 'utama' ? 'selected' : '' ?>>Utama</option>
                                    <option value="tambahan" <?= $rhk['klasifikasi'] == 'tambahan' ? 'selected' : '' ?>>Tambahan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Hasil Kerja <span class="text-danger">*</span></label>
                                <textarea name="nama_rhk" class="form-control" rows="3" required><?= esc($rhk['nama_rhk']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bobot (%) <span class="text-danger">*</span></label>
                                <input type="number" name="bobot" class="form-control" min="0" max="100" step="5" value="<?= $rhk['bobot'] ?>" required>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update RHK</button>
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
