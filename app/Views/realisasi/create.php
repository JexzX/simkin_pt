<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'realisasi']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Input Realisasi</h5>
        </nav>

        <div class="p-4">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-secondary">
                        <strong>RHK:</strong> <?= $rhk['nama_rhk'] ?><br>
                        <strong>Indikator:</strong> <?= $indikator['indikator'] ?> (Target: <?= $indikator['target'] ?>)
                    </div>

                    <form action="<?= base_url('/realisasi/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="rhk_indikator_id" value="<?= $indikator['id'] ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Bulan <span class="text-danger">*</span></label>
                                    <select name="bulan" class="form-control" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <?php for($i=1; $i<=12; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($bulan == $i) ? 'selected' : '' ?>>
                                            <?= date('F', mktime(0,0,0,$i,1)) ?>
                                        </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tahun</label>
                                    <input type="text" name="tahun" class="form-control" value="<?= date('Y') ?>"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <?php if($rhk['jenis_rhk'] == 'kuantitatif'): ?>
                        <div class="mb-3">
                            <label class="form-label">Realisasi Kuantitas</label>
                            <input type="number" name="realisasi_kuantitas" class="form-control"
                                value="<?= $existing['realisasi_kuantitas'] ?? '' ?>" placeholder="Contoh: 75">
                            <small class="text-muted">Satuan: <?= $rhk['target_satuan'] ?></small>
                        </div>
                        <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label">Realisasi Kualitas</label>
                            <textarea name="realisasi_kualitas" class="form-control" rows="3"
                                placeholder="Deskripsikan realisasi yang telah dicapai"><?= $existing['realisasi_kualitas'] ?? '' ?></textarea>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Realisasi</label>
                            <input type="date" name="realisasi_waktu" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bukti/Files</label>
                            <input type="file" name="bukti_file" class="form-control">
                            <small class="text-muted">Upload file pendukung (PDF, JPG, PNG, max 5MB)</small>
                            <?php if(isset($existing['bukti_file']) && $existing['bukti_file']): ?>
                            <div class="mt-2">
                                <a href="<?= base_url($existing['bukti_file']) ?>" target="_blank">Lihat file
                                    sebelumnya</a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2"
                                placeholder="Catatan tambahan (opsional)"><?= $existing['catatan'] ?? '' ?></textarea>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-1"></i> Simpan Realisasi
                        </button>
                        <a href="<?= base_url('/realisasi') ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>