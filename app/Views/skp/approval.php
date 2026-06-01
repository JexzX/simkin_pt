<?= $this->extend('layout/header') ?>
<?= $this->section('content') ?>

<div class="d-flex">
    <?= view('layout/sidebar', ['active_menu' => 'approval']) ?>

    <div class="content-wrapper w-100">
        <nav class="navbar-top">
            <h5 class="mb-0"><i class="fas fa-check-double me-2"></i> Persetujuan SKP</h5>
        </nav>

        <div class="p-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pegawai</th>
                                    <th>Unit Kerja</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($skpList as $skp): ?>
                                <tr>
                                    <td><?= $skp['user_name'] ?? '-' ?></td>
                                    <td><?= $skp['unit_kerja'] ?? '-' ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($skp['tanggal_pengajuan'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                                            class="btn btn-sm btn-primary">Review</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($skpList)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada SKP yang menunggu persetujuan</td>
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