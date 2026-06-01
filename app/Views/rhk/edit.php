<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit RHK</h5>
        </nav>

        <div class="p-4">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('/rhk/update/' . $rhk['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="skp_id" value="<?= $skp_id ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama RHK <span class="text-danger">*</span></label>
                            <input type="text" name="nama_rhk" class="form-control" value="<?= $rhk['nama_rhk'] ?>"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis RHK</label>
                                    <select name="jenis_rhk" id="jenis_rhk" class="form-control">
                                        <option value="kuantitatif"
                                            <?= $rhk['jenis_rhk'] == 'kuantitatif' ? 'selected' : '' ?>>Kuantitatif
                                        </option>
                                        <option value="kualitatif"
                                            <?= $rhk['jenis_rhk'] == 'kualitatif' ? 'selected' : '' ?>>Kualitatif
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Klasifikasi</label>
                                    <select name="klasifikasi" class="form-control">
                                        <option value="utama" <?= $rhk['klasifikasi'] == 'utama' ? 'selected' : '' ?>>
                                            Utama</option>
                                        <option value="tambahan"
                                            <?= $rhk['klasifikasi'] == 'tambahan' ? 'selected' : '' ?>>Tambahan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="target_kuantitatif"
                            <?= $rhk['jenis_rhk'] != 'kuantitatif' ? 'style="display:none"' : '' ?>>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Target Kuantitas</label>
                                    <input type="number" name="target_kuantitas" class="form-control"
                                        value="<?= $rhk['target_kuantitas'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" name="target_satuan" class="form-control"
                                        value="<?= $rhk['target_satuan'] ?>">
                                </div>
                            </div>
                        </div>

                        <div id="target_kualitatif"
                            <?= $rhk['jenis_rhk'] != 'kualitatif' ? 'style="display:none"' : '' ?>>
                            <div class="mb-3">
                                <label class="form-label">Target Kualitas</label>
                                <textarea name="target_kualitas" class="form-control"
                                    rows="3"><?= $rhk['target_kualitas'] ?></textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Waktu</label>
                            <input type="date" name="target_waktu" class="form-control"
                                value="<?= $rhk['target_waktu'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bobot (%)</label>
                            <input type="number" name="bobot" class="form-control" min="0" max="100" step="5"
                                value="<?= $rhk['bobot'] ?>">
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-1"></i> Update RHK
                        </button>
                        <a href="<?= base_url('/skp/detail/' . $skp_id) ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#jenis_rhk').change(function() {
    if ($(this).val() == 'kuantitatif') {
        $('#target_kuantitatif').show();
        $('#target_kualitatif').hide();
    } else {
        $('#target_kuantitatif').hide();
        $('#target_kualitatif').show();
    }
});
</script>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>