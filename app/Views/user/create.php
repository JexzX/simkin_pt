<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'user']) ?>

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
                                        <option value="<?= $u['id'] ?>"><?= $u['nama_lengkap'] ?> (<?= $u['role'] ?>)</option>
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

<?= $this->endSection() ?>
<?= view('layout/footer') ?>