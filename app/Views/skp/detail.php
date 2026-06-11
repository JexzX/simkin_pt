<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail SKP - SIMKIN UIN Salatiga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Detail SKP</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID SKP</th>
                        <td><?= $skp['id'] ?></td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td><?= $skp['periode_id'] ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= $skp['status'] ?></td>
                    </tr>
                    <tr>
                        <th>Total Bobot</th>
                        <td><?= $totalBobot ?>%</td </tr>
                </table>

                <h6 class="mt-4">Daftar RHK</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama RHK</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($rhkList as $rhk): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $rhk['nama_rhk'] ?></td>
                            <td><?= $rhk['bobot'] ?>%</td <td>
                            <?php if($skp['status'] == 'draft'): ?>
                            <a href="<?= base_url('/rhk/edit/' . $rhk['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= base_url('/rhk/delete/' . $rhk['id']) ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus?')">Hapus</a>
                            <?php endif; ?>
                            </td </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="mt-3">
                    <!-- Tombol untuk PEMBUAT SKP (Ajukan) -->
                    <?php if($skp['status'] == 'draft' && session()->get('id') == $skp['user_id']): ?>
                    <?php if($totalBobot == 100): ?>
                    <form action="<?= base_url('/skp/submit/' . $skp['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Ajukan SKP ini ke atasan?')">Ajukan SKP</button>
                    </form>
                    <?php else: ?>
                    <button class="btn btn-secondary" disabled>Bobot harus 100% (<?= $totalBobot ?>%)</button>
                    <?php endif; ?>
                    <a href="<?= base_url('/rhk/create/' . $skp['id']) ?>" class="btn btn-primary">Tambah RHK</a>
                    <?php endif; ?>

                    <!-- Tombol untuk ATASAN (Setujui/Tolak) -->
                    <?php 
                    $currentUserId = session()->get('id');
                    $pembuatSkpId = $skp['user_id'];
                    $isAtasan = false;
                    
                    // Cek apakah user yang login adalah atasan dari pembuat SKP
                    if($pembuatSkpId != $currentUserId) {
                        $userModel = new \App\Models\UserModel();
                        $pembuat = $userModel->find($pembuatSkpId);
                        if($pembuat && $pembuat['atasan_id'] == $currentUserId) {
                            $isAtasan = true;
                        }
                    }
                    // Rektor bisa approve semua
                    if(session()->get('role') == 'rektor') {
                        $isAtasan = true;
                    }
                    ?>

                    <?php if($skp['status'] == 'menunggu_approval' && $isAtasan && $currentUserId != $pembuatSkpId): ?>
                    <form action="<?= base_url('/approval/skp/approve/' . $skp['id']) ?>" method="post"
                        class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Setujui SKP ini?')">Setujui</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#rejectModal">Tolak</button>
                    <?php endif; ?>

                    <a href="<?= base_url('/skp') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('/approval/skp/reject/' . $skp['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak SKP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan penolakan..."
                            required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>