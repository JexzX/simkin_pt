<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="d-flex">
        <?= view('layout/sidebar', ['active_menu' => 'user']) ?>
        <div class="content-wrapper w-100">
            <nav class="navbar-top px-4 py-3 bg-white shadow-sm">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Manajemen User</h5>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Daftar User</h6>
                            <a href="<?= base_url('/user/create') ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah User</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="50">ID</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Unit Kerja</th>
                                        <th>Jabatan</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $u): ?>
                                    <tr>
                                        <td class="text-center"><?= $u['id'] ?></td>
                                        <td><?= esc($u['username']) ?></td>
                                        <td><?= esc($u['nama_lengkap']) ?></td>
                                        <td><?= esc($u['unit_kerja']) ?></td>
                                        <td><?= esc($u['jabatan']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $roleClass = match($u['role']) {
                                                'super_admin' => 'danger',
                                                'admin_perencana' => 'info',
                                                'rektor' => 'primary',
                                                'dekan' => 'warning',
                                                'kaprodi' => 'success',
                                                'dosen' => 'secondary',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $roleClass ?>"><?= ucfirst(str_replace('_', ' ', $u['role'])) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= ($u['status']=='aktif')?'success':'danger' ?>"><?= ucfirst($u['status']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/user/edit/' . $u['id']) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('/user/reset-password/' . $u['id']) ?>" class="btn btn-sm btn-info" title="Reset Password" onclick="return confirm('Reset password user ini?')"><i class="fas fa-key"></i></a>
                                            <a href="<?= base_url('/user/delete/' . $u['id']) ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Hapus user ini?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($users)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data user</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
