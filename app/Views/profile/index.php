<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'profil']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i> Profil Saya</h5>
            </nav>
            <div class="p-4">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-id-card me-2"></i>Data Profil</div>
                            <div class="card-body">
                                <form action="<?= base_url('/profil/update') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" value="<?= $user['username'] ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_lengkap" class="form-control" required value="<?= $user['nama_lengkap'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">NIP</label>
                                        <input type="text" name="nip" class="form-control" value="<?= $user['nip'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= $user['email'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jabatan</label>
                                        <input type="text" name="jabatan" class="form-control" value="<?= $user['jabatan'] ?>">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Unit Kerja</label>
                                            <input type="text" class="form-control" value="<?= $user['unit_kerja'] ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="<?= ucfirst($user['role']) ?>" disabled>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Profil</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-key me-2"></i>Ganti Password</div>
                            <div class="card-body">
                                <form action="<?= base_url('/profil/change-password') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="mb-3">
                                        <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                                        <input type="password" name="password_lama" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                                        <input type="password" name="password_baru" class="form-control" required>
                                        <small class="text-muted">Minimal 6 karakter</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                        <input type="password" name="konfirmasi" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning"><i class="fas fa-key"></i> Ganti Password</button>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Informasi Akun</div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr><td>Username</td><td><strong><?= $user['username'] ?></strong></td></tr>
                                    <tr><td>Role</td><td><span class="badge bg-primary"><?= ucfirst($user['role']) ?></span></td></tr>
                                    <tr><td>Unit Kerja</td><td><?= $user['unit_kerja'] ?></td></tr>
                                    <tr><td>Status</td><td><span class="badge bg-<?= ($user['status']=='aktif')?'success':'danger' ?>"><?= ucfirst($user['status']) ?></span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
