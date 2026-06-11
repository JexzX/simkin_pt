<!DOCTYPE html>
<html>

<head>
    <title>Daftar SKP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Daftar SKP</h5>
            </div>
            <div class="card-body">
                <a href="<?= base_url('/skp/create') ?>" class="btn btn-primary mb-3">Buat SKP Baru</a>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($skpList as $skp): ?>
                        <tr>
                            <td><?= $skp['id'] ?></td>
                            <td><?= $skp['periode_id'] ?></td>
                            <td><?= $skp['status'] ?></td>
                            <td><a href="<?= base_url('/skp/detail/' . $skp['id']) ?>"
                                    class="btn btn-sm btn-info">Detail</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>

</html>