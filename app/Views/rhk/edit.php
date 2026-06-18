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

                            <?php if($isRektor): ?>
                            <div class="mb-4 p-3 bg-light rounded">
                                <label class="form-label fw-semibold"><i class="fas fa-arrow-down me-1"></i> Dasar RHK / Sumber Intervensi</label>
                                <textarea name="intervensi_dari_manual" class="form-control" rows="2"><?= esc($rhk['intervensi_dari_manual'] ?? '') ?></textarea>
                            </div>
                            <?php elseif(!empty($intervensiList)): ?>
                            <div class="mb-4 p-3 bg-light rounded">
                                <label class="form-label fw-semibold"><i class="fas fa-arrow-down me-1"></i> RHK Atasan yang Diintervensi</label>
                                <select name="intervensi_dari_id" class="form-select" id="intervensiRhk">
                                    <option value="">-- Pilih RHK Atasan (opsional) --</option>
                                    <?php foreach($intervensiList as $intervensi): ?>
                                    <option value="<?= $intervensi['id'] ?>"
                                        data-indikator='<?= htmlspecialchars(json_encode($intervensi['indikator'] ?? []), ENT_QUOTES, 'UTF-8') ?>'
                                        <?= ($selectedRhkId == $intervensi['id']) ? 'selected' : '' ?>>
                                        <?= esc($intervensi['nama_rhk']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Pilih RHK atasan yang akan diintervensi</small>

                                <div id="indikatorAtasanContainer" class="mt-3 <?= $selectedIndikatorId ? '' : 'd-none' ?>">
                                    <hr>
                                    <label class="form-label fw-semibold">Pilih 1 Indikator yang Diintervensi:</label>
                                    <div id="indikatorAtasanList"></div>
                                </div>
                            </div>
                            <?php endif; ?>

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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectRhk = document.getElementById('intervensiRhk');
        if (selectRhk) {
            selectRhk.addEventListener('change', function() {
                const container = document.getElementById('indikatorAtasanContainer');
                const list = document.getElementById('indikatorAtasanList');
                const selected = this.options[this.selectedIndex];

                if (!selected || !selected.value) {
                    container.classList.add('d-none');
                    return;
                }

                let indikator = [];
                try {
                    indikator = JSON.parse(selected.getAttribute('data-indikator') || '[]');
                } catch (e) { indikator = []; }

                if (indikator.length === 0) {
                    container.classList.add('d-none');
                    return;
                }

                container.classList.remove('d-none');

                const selectedIndId = <?= json_encode($selectedIndikatorId) ?>;

                list.innerHTML = indikator.map(function(i, idx) {
                    const checked = (parseInt(selectedIndId) === parseInt(i.id)) ? 'checked' : '';
                    return '<div class="form-check">' +
                        '<input class="form-check-input" type="radio" name="intervensi_indikator_id" value="' +
                        i.id + '" id="ind_' + idx + '" ' + checked + ' required>' +
                        '<label class="form-check-label" for="ind_' + idx + '">' +
                        '<span class="badge bg-info me-1">' + (i.aspek || '-') + '</span> ' +
                        i.indikator + ' <small class="text-muted">(' + (i.target || '-') +
                        ')</small>' +
                        '</label></div>';
                }).join('');
            });

            // Trigger change on load if there's a selected RHK
            if (selectRhk.value) {
                selectRhk.dispatchEvent(new Event('change'));
            }
        }
    });
    </script>
</body>
</html>