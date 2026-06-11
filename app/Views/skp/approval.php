<!DOCTYPE html>
<html>

<head>
    <title>Persetujuan SKP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5>Persetujuan SKP</h5>
            </div>
            <div class="card-body">
                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <table class="table table-bordered">
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
                            <td><?= $skp['user_name'] ?></td>
                            <td><?= $skp['unit_kerja'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($skp['tanggal_pengajuan'])) ?></td>
                            <td>
                                <a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                                    class="btn btn-sm btn-primary">Review</a>
                            </td </tr>
                            <?php endforeach; ?>
                            <?php if(empty($skpList)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada SKP yang menunggu persetujuan</td </tr>
                            <?php endif; ?>
                    </tbody>
                </table>
                <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>

</html>