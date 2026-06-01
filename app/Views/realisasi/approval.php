<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'approval_realisasi']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top">
            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i> Persetujuan Realisasi</h5>
        </nav>

        <div class="p-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pegawai</th>
                                    <th>RHK</th>
                                    <th>Bulan</th>
                                    <th>Realisasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($realisasiList as $real): ?>
                                <tr>
                                    <td><?= $real['user_name'] ?></td>
                                    <td><?= $real['nama_rhk'] ?></td>
                                    <td><?= $real['bulan'] ?></td>
                                    <td>
                                        <?php if($real['realisasi_kuantitas']): ?>
                                        <?= $real['realisasi_kuantitas'] ?>
                                        <?php else: ?>
                                        <?= substr($real['realisasi_kualitas'], 0, 50) ?>...
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="<?= base_url('/realisasi/approve/' . $real['id']) ?>"
                                            method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Setujui realisasi ini?')">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal<?= $real['id'] ?>">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>

                                        <!-- Modal Tolak -->
                                        <div class="modal fade" id="rejectModal<?= $real['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="<?= base_url('/realisasi/reject/' . $real['id']) ?>"
                                                        method="post">
                                                        <?= csrf_field() ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Realisasi</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Catatan Penolakan</label>
                                                                <textarea name="catatan" class="form-control" rows="3"
                                                                    required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($realisasiList)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada realisasi yang menunggu persetujuan
                                    </td>
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

<?= $this->endSection() ?>
<?= view('layout/footer') ?>