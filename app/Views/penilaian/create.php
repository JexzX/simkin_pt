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

                <form action="<?= base_url('/penilaian/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="skp_id" value="<?= $skp['id'] ?>">

                    <?php if(!empty($rhkList)): ?>
                    <?php foreach($rhkList as $rhk): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-list-check me-2"></i><?= esc($rhk['nama_rhk']) ?></h6>
                            <small class="text-muted"><?= ucfirst($rhk['klasifikasi']) ?></small>
                        </div>
                        <div class="card-body p-0">
                            <?php $indikators = $rhkIndikators[$rhk['id']] ?? []; ?>
                            <?php if(!empty($indikators)): ?>
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px">No</th>
                                        <th>Indikator</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th style="width:150px">Nilai (0-100)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($indikators as $ind): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if($ind['aspek']): ?>
                                            <span class="badge bg-info me-1"><?= esc($ind['aspek']) ?></span>
                                            <?php endif; ?>
                                            <?= esc($ind['indikator']) ?>
                                        </td>
                                        <td><?= esc($ind['target'] ?? '-') ?></td>
                                        <td><?= $ind['total_realisasi'] ?: '-' ?></td>
                                        <td>
                                            <input type="number" name="nilai_indikator[<?= $ind['id'] ?>]" class="form-control text-center nilai-input" min="0" max="100" value="<?= $rincianNilai[$ind['id']] ?? '' ?>" required>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="p-3 text-muted fst-italic">Tidak ada indikator</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Rata-rata Nilai</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-lg fw-bold text-center" id="rataNilai" value="<?= $existing['nilai_total'] ?? '0' ?>" readonly style="font-size:1.5rem">
                                        <span class="input-group-text fw-semibold" id="predikatLabel"><?= $existing['predikat'] ?? '-' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan Penilai</label>
                                <textarea name="catatan_penilai" class="form-control" rows="4" placeholder="Catatan penilaian (opsional)"><?= $existing['catatan_penilai'] ?? '' ?></textarea>
                            </div>

                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan penilaian ini?')"><i class="fas fa-save me-2"></i>Simpan Penilaian</button>
                                <a href="<?= base_url('/penilaian') ?>" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Batal</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.nilai-input');
        const rataInput = document.getElementById('rataNilai');
        const predikatLabel = document.getElementById('predikatLabel');

        function hitungRata() {
            let total = 0, count = 0;
            inputs.forEach(function(inp) {
                let v = parseFloat(inp.value);
                if (!isNaN(v) && v >= 0 && v <= 100) {
                    total += v;
                    count++;
                }
            });
            let rata = count > 0 ? (total / count) : 0;
            rata = Math.round(rata * 100) / 100;
            rataInput.value = rata;

            let predikat = '-';
            if (rata >= 91) predikat = 'ISTIMEWA';
            else if (rata >= 76) predikat = 'BAIK';
            else if (rata >= 61) predikat = 'CUKUP';
            else if (rata >= 51) predikat = 'KURANG';
            else if (rata > 0) predikat = 'BURUK';
            predikatLabel.textContent = predikat;
        }

        inputs.forEach(function(inp) {
            inp.addEventListener('input', hitungRata);
        });

        hitungRata();
    });
    </script>
</body>
</html>