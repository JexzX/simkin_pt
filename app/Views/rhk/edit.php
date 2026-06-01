<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit RHK - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .sidebar {
        min-height: 100vh;
        background: #2c3e50;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
    }

    .sidebar .nav-link:hover {
        background: #1a252f;
        color: white;
    }

    .navbar-top {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 15px 20px;
    }

    .content-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
    }
    </style>
</head>

<body>
    <div class="d-flex">
        <div class="sidebar" style="width: 260px;">
            <div class="p-4 text-center text-white border-bottom border-secondary">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-0">SIMKIN UIN</h5>
                <small>Salatiga</small>
            </div>
            <ul class="nav flex-column p-3">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/dashboard') ?>"><i
                            class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/skp') ?>"><i
                            class="fas fa-file-alt me-2"></i> SKP / RHK</a></li>
                <li class="nav-item mt-3"><a class="nav-link text-danger" href="<?= base_url('/logout') ?>"><i
                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>

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
                                <label class="form-label">Nama RHK</label>
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
                                            <option value="utama"
                                                <?= $rhk['klasifikasi'] == 'utama' ? 'selected' : '' ?>>Utama</option>
                                            <option value="tambahan"
                                                <?= $rhk['klasifikasi'] == 'tambahan' ? 'selected' : '' ?>>Tambahan
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="target_kuantitatif"
                                <?= $rhk['jenis_rhk'] != 'kuantitatif' ? 'style="display:none"' : '' ?>>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3"><label>Target Kuantitas</label><input type="number"
                                                name="target_kuantitas" class="form-control"
                                                value="<?= $rhk['target_kuantitas'] ?>"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3"><label>Satuan</label><input type="text" name="target_satuan"
                                                class="form-control" value="<?= $rhk['target_satuan'] ?>"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="target_kualitatif"
                                <?= $rhk['jenis_rhk'] != 'kualitatif' ? 'style="display:none"' : '' ?>>
                                <div class="mb-3"><label>Target Kualitas</label><textarea name="target_kualitas"
                                        class="form-control" rows="3"><?= $rhk['target_kualitas'] ?></textarea></div>
                            </div>

                            <div class="mb-3"><label>Bobot (%)</label><input type="number" name="bobot"
                                    class="form-control" min="0" max="100" step="5" value="<?= $rhk['bobot'] ?>"></div>

                            <hr>
                            <button type="submit" class="btn btn-primary">Update RHK</button>
                            <a href="<?= base_url('/skp/detail/' . $skp_id) ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('jenis_rhk').addEventListener('change', function() {
        document.getElementById('target_kuantitatif').style.display = this.value === 'kuantitatif' ? 'block' :
            'none';
        document.getElementById('target_kualitatif').style.display = this.value === 'kualitatif' ? 'block' :
            'none';
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>