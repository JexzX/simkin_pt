<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Periode - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'periode']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Periode</h5>
            </nav>
            <div class="p-4">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url('/periode/update/' . $periode['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Periode <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_periode" class="form-control" required value="<?= $periode['nama_periode'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                                        <input type="number" name="tahun" class="form-control" required value="<?= $periode['tahun'] ?>" min="2020" max="2050">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_mulai" class="form-control" required value="<?= $periode['tanggal_mulai'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_selesai" class="form-control" required value="<?= $periode['tanggal_selesai'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Batas Akhir Pengajuan SKP</label>
                                        <input type="date" name="batas_akhir_pengajuan_skp" class="form-control" value="<?= $periode['batas_akhir_pengajuan_skp'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Batas Akhir Realisasi</label>
                                        <input type="date" name="batas_akhir_realisasi" class="form-control" value="<?= $periode['batas_akhir_realisasi'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Batas Akhir Penilaian</label>
                                        <input type="date" name="batas_akhir_penilaian" class="form-control" value="<?= $periode['batas_akhir_penilaian'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActive" <?= $periode['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="isActive">Aktifkan sebagai periode berjalan</label>
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                            <a href="<?= base_url('/periode') ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
