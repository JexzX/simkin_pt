<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'user']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah User</h5>
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
                        <form action="<?= base_url('/user/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" required placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">NIP</label>
                                    <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="contoh@email.com">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Unit Kerja <span class="text-danger">*</span></label>
                                    <select name="unit_kerja" class="form-select" required>
                                        <option value="">-- Pilih Unit Kerja --</option>
                                        <?php foreach($units as $unit): ?>
                                        <option value="<?= $unit['nama_unit'] ?>"><?= $unit['nama_unit'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" name="jabatan" class="form-control" required placeholder="Masukkan jabatan">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="super_admin">Super Admin</option>
                                        <option value="admin_perencana">Admin Perencana</option>
                                        <option value="rektor">Rektor</option>
                                        <option value="dekan">Dekan</option>
                                        <option value="kaprodi">Kaprodi</option>
                                        <option value="dosen">Dosen</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Atasan</label>
                                    <select name="atasan_id" class="form-select">
                                        <option value="">-- Tanpa Atasan --</option>
                                        <?php foreach($users as $u): ?>
                                        <option value="<?= $u['id'] ?>"><?= esc($u['nama_lengkap']) ?> (<?= ucfirst(str_replace('_', ' ', $u['role'])) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                            <a href="<?= base_url('/user') ?>" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
