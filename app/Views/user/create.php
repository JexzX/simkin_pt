<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - SIMKIN UIN Salatiga</title>
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

    .sidebar .nav-link.active {
        background: #667eea;
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
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('/user') ?>"><i
                            class="fas fa-users me-2"></i> Manajemen User</a></li>
                <li class="nav-item mt-3"><a class="nav-link text-danger" href="<?= base_url('/logout') ?>"><i
                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="content-wrapper w-100">
            <nav class="navbar-top">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah User Baru</h5>
            </nav>
            <div class="p-4">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= base_url('/user/store') ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" required>
                                        <small class="text-muted">Minimal 6 karakter</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NIP</label>
                                        <input type="text" name="nip" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                                        <select name="unit_kerja" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="UIN Salatiga">UIN Salatiga</option>
                                            <option value="FTIK">FTIK</option>
                                            <option value="FEBI">FEBI</option>
                                            <option value="FD">FD</option>
                                            <option value="FS">FS</option>
                                            <option value="FUAH">FUAH</option>
                                            <option value="FST">FST</option>
                                            <option value="BIRO">BIRO</option>
                                            <option value="LPM">LPM</option>
                                            <option value="LP2M">LP2M</option>
                                            <option value="UPT TIPD">UPT TIPD</option>
                                            <option value="UPT Perpus">UPT Perpus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                        <input type="text" name="jabatan" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Role <span class="text-danger">*</span></label>
                                        <select name="role" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="super_admin">Super Admin</option>
                                            <option value="admin_perencana">Admin Perencana</option>
                                            <option value="rektor">Rektor</option>
                                            <option value="dekan">Dekan</option>
                                            <option value="kaprodi">Kaprodi</option>
                                            <option value="dosen">Dosen</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Atasan</label>
                                        <select name="atasan_id" class="form-control">
                                            <option value="">-- Tanpa Atasan --</option>
                                            <?php foreach($users as $u): ?>
                                            <option value="<?= $u['id'] ?>"><?= $u['nama_lengkap'] ?>
                                                (<?= $u['role'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary">Simpan User</button>
                            <a href="<?= base_url('/user') ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>