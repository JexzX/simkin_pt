<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'skp']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top">
            <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah RHK</h5>
        </nav>

        <div class="p-4">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('/rhk/store') ?>" method="post" id="formRhk">
                        <?= csrf_field() ?>
                        <input type="hidden" name="skp_id" value="<?= $skp_id ?>">

                        <!-- Intervensi dari Atasan -->
                        <?php if(isset($intervensiList) && !empty($intervensiList)): ?>
                        <div class="mb-3">
                            <label class="form-label">Rencana Hasil Kerja Atasan yang Diintervensi</label>
                            <select name="intervensi_id" id="intervensi_id" class="form-control">
                                <option value="">-- Pilih Intervensi --</option>
                                <?php foreach($intervensiList as $iksk): ?>
                                <option value="<?= $iksk['id'] ?>">
                                    <?= $iksk['kode_iksk'] ?> - <?= substr($iksk['nama_iksk'], 0, 80) ?>...
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="intervensi_type" value="iksk">
                        </div>
                        <?php endif; ?>

                        <!-- Nama RHK -->
                        <div class="mb-3">
                            <label class="form-label">Nama RHK <span class="text-danger">*</span></label>
                            <input type="text" name="nama_rhk" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis RHK <span class="text-danger">*</span></label>
                                    <select name="jenis_rhk" id="jenis_rhk" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="kuantitatif">Kuantitatif (dapat diukur dengan angka)</option>
                                        <option value="kualitatif">Kualitatif (deskriptif)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Klasifikasi <span class="text-danger">*</span></label>
                                    <select name="klasifikasi" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="utama">Utama</option>
                                        <option value="tambahan">Tambahan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Target Kuantitatif -->
                        <div id="target_kuantitatif" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Target Kuantitas</label>
                                        <input type="number" name="target_kuantitas" class="form-control"
                                            placeholder="Contoh: 100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="target_satuan" class="form-control"
                                            placeholder="Contoh: %, orang, dokumen">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Kualitatif -->
                        <div id="target_kualitatif" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Target Kualitas</label>
                                <textarea name="target_kualitas" class="form-control" rows="3"
                                    placeholder="Deskripsikan target yang ingin dicapai"></textarea>
                            </div>
                        </div>

                        <!-- Target Waktu -->
                        <div class="mb-3">
                            <label class="form-label">Target Waktu</label>
                            <input type="date" name="target_waktu" class="form-control">
                        </div>

                        <!-- Bobot -->
                        <div class="mb-3">
                            <label class="form-label">Bobot (%)</label>
                            <input type="number" name="bobot" class="form-control" min="0" max="100" step="5" value="0">
                            <small class="text-muted">Total bobot semua RHK harus 100%</small>
                        </div>

                        <!-- Indikator -->
                        <div class="mb-3">
                            <label class="form-label">Indikator</label>
                            <div id="indikatorContainer">
                                <div class="row mb-2 indikator-row">
                                    <div class="col-md-8">
                                        <input type="text" name="indikator[]" class="form-control"
                                            placeholder="Indikator">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="target_indikator[]" class="form-control"
                                            placeholder="Target">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-indikator"
                                            style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addIndikator" class="btn btn-sm btn-secondary mt-2">
                                <i class="fas fa-plus me-1"></i> Tambah Indikator
                            </button>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save me-1"></i> Simpan RHK
                        </button>
                        <a href="<?= base_url('/skp/detail/' . $skp_id) ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tampilkan target sesuai jenis RHK
    $('#jenis_rhk').change(function() {
        if ($(this).val() == 'kuantitatif') {
            $('#target_kuantitatif').show();
            $('#target_kualitatif').hide();
        } else if ($(this).val() == 'kualitatif') {
            $('#target_kuantitatif').hide();
            $('#target_kualitatif').show();
        } else {
            $('#target_kuantitatif').hide();
            $('#target_kualitatif').hide();
        }
    });

    // Tambah indikator
    $('#addIndikator').click(function() {
        var newRow = $('.indikator-row:first').clone();
        newRow.find('input').val('');
        newRow.find('.remove-indikator').show();
        $('#indikatorContainer').append(newRow);
    });

    // Hapus indikator
    $(document).on('click', '.remove-indikator', function() {
        if ($('.indikator-row').length > 1) {
            $(this).closest('.indikator-row').remove();
        }
    });
});
</script>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>