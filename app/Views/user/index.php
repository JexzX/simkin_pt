<!DOCTYPE html>
<html>

<head>
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>Manajemen User</h5>
            </div>
            <div class="card-body">
                <a href="<?= base_url('/user/create') ?>" class="btn btn-primary mb-3">Tambah User</a>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['nama_lengkap'] ?></td>
                            <td><?= $user['role'] ?></td>
                            <td>
                                <a href="<?= base_url('/user/edit/' . $user['id']) ?>"
                                    class="btn btn-sm btn-warning">Edit</a>
                            </td </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>

</html>