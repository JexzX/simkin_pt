<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'user']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit User</h5>
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
                        <form action="<?= base_url('/user/update/' . $user['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= esc($user['nama_lengkap']) ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">NIP</label>
                                    <input type="text" name="nip" class="form-control" value="<?= esc($user['nip'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Unit Kerja <span class="text-danger">*</span></label>
                                    <select name="unit_kerja" class="form-select" required>
                                        <option value="">-- Pilih Unit Kerja --</option>
                                        <?php
                                        $units = ['UIN Salatiga','FTIK','FEBI','FD','FS','FUAH','FST','Dakwah','BIRO','LPM','LP2M','UPT TIPD','UPT Perpus'];
                                        foreach($units as $unit):
                                        ?>
                                        <option value="<?= $unit ?>" <?= ($user['unit_kerja']==$unit)?'selected':'' ?>><?= $unit ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" name="jabatan" class="form-control" value="<?= esc($user['jabatan']) ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="super_admin" <?= ($user['role']=='super_admin')?'selected':'' ?>>Super Admin</option>
                                        <option value="admin_perencana" <?= ($user['role']=='admin_perencana')?'selected':'' ?>>Admin Perencana</option>
                                        <option value="rektor" <?= ($user['role']=='rektor')?'selected':'' ?>>Rektor</option>
                                        <option value="dekan" <?= ($user['role']=='dekan')?'selected':'' ?>>Dekan</option>
                                        <option value="kaprodi" <?= ($user['role']=='kaprodi')?'selected':'' ?>>Kaprodi</option>
                                        <option value="dosen" <?= ($user['role']=='dosen')?'selected':'' ?>>Dosen</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">Atasan</label>
                                    <select name="atasan_id" class="form-select">
                                        <option value="">-- Tanpa Atasan --</option>
                                        <?php foreach($users as $u): ?>
                                        <option value="<?= $u['id'] ?>" <?= ($user['atasan_id']==$u['id'])?'selected':'' ?>><?= esc($u['nama_lengkap']) ?> (<?= ucfirst(str_replace('_', ' ', $u['role'])) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="aktif" <?= ($user['status']=='aktif')?'selected':'' ?>>Aktif</option>
                                    <option value="nonaktif" <?= ($user['status']=='nonaktif')?'selected':'' ?>>Nonaktif</option>
                                </select>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update</button>
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
