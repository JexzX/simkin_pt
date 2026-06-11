<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'user']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit User</h5>
        </nav>

        <div class="p-4">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('/user/update/' . $user['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control"
                                        value="<?= $user['username'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                value="<?= $user['nama_lengkap'] ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="text" name="nip" class="form-control" value="<?= $user['nip'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Unit Kerja</label>
                                    <select name="unit_kerja" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="UIN Salatiga"
                                            <?= $user['unit_kerja'] == 'UIN Salatiga' ? 'selected' : '' ?>>UIN Salatiga
                                        </option>
                                        <option value="FTIK" <?= $user['unit_kerja'] == 'FTIK' ? 'selected' : '' ?>>FTIK
                                        </option>
                                        <option value="FEBI" <?= $user['unit_kerja'] == 'FEBI' ? 'selected' : '' ?>>FEBI
                                        </option>
                                        <option value="FD" <?= $user['unit_kerja'] == 'FD' ? 'selected' : '' ?>>FD
                                        </option>
                                        <option value="FS" <?= $user['unit_kerja'] == 'FS' ? 'selected' : '' ?>>FS
                                        </option>
                                        <option value="FUAH" <?= $user['unit_kerja'] == 'FUAH' ? 'selected' : '' ?>>FUAH
                                        </option>
                                        <option value="FST" <?= $user['unit_kerja'] == 'FST' ? 'selected' : '' ?>>FST
                                        </option>
                                        <option value="BIRO" <?= $user['unit_kerja'] == 'BIRO' ? 'selected' : '' ?>>BIRO
                                        </option>
                                        <option value="LPM" <?= $user['unit_kerja'] == 'LPM' ? 'selected' : '' ?>>LPM
                                        </option>
                                        <option value="LP2M" <?= $user['unit_kerja'] == 'LP2M' ? 'selected' : '' ?>>LP2M
                                        </option>
                                        <option value="UPT TIPD"
                                            <?= $user['unit_kerja'] == 'UPT TIPD' ? 'selected' : '' ?>>UPT TIPD</option>
                                        <option value="UPT Perpus"
                                            <?= $user['unit_kerja'] == 'UPT Perpus' ? 'selected' : '' ?>>UPT Perpus
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control"
                                        value="<?= $user['jabatan'] ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="super_admin"
                                            <?= $user['role'] == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                        <option value="admin_perencana"
                                            <?= $user['role'] == 'admin_perencana' ? 'selected' : '' ?>>Admin Perencana
                                        </option>
                                        <option value="rektor" <?= $user['role'] == 'rektor' ? 'selected' : '' ?>>Rektor
                                        </option>
                                        <option value="dekan" <?= $user['role'] == 'dekan' ? 'selected' : '' ?>>Dekan
                                        </option>
                                        <option value="kaprodi" <?= $user['role'] == 'kaprodi' ? 'selected' : '' ?>>
                                            Kaprodi</option>
                                        <option value="dosen" <?= $user['role'] == 'dosen' ? 'selected' : '' ?>>Dosen
                                        </option>
                                        <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Atasan</label>
                                    <select name="atasan_id" class="form-control">
                                        <option value="">-- Tanpa Atasan --</option>
                                        <?php foreach($users as $u): ?>
                                        <option value="<?= $u['id'] ?>"
                                            <?= ($user['atasan_id'] == $u['id']) ? 'selected' : '' ?>>
                                            <?= $u['nama_lengkap'] ?> (<?= $u['role'] ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="aktif" <?= $user['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= $user['status'] == 'nonaktif' ? 'selected' : '' ?>>Nonaktif
                                </option>
                            </select>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="<?= base_url('/user') ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= view('layout/footer') ?>