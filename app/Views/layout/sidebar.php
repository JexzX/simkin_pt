<div class="sidebar">
    <div class="header">
        <h5>SIMKIN UIN</h5>
        <small>Salatiga</small>
    </div>
    <a href="<?= base_url('/dashboard') ?>">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="<?= base_url('/skp') ?>">
        <i class="fas fa-file-alt"></i> SKP / RHK
    </a>
    <a href="<?= base_url('/realisasi') ?>">
        <i class="fas fa-chart-line"></i> Realisasi
    </a>

    <!-- Menu Persetujuan SKP untuk Rektor, Dekan, Kaprodi -->
    <?php $role = session()->get('role'); ?>
    <?php if(in_array($role, ['rektor', 'dekan', 'kaprodi', 'super_admin'])): ?>
    <a href="<?= base_url('/approval/skp') ?>">
        <i class="fas fa-check-double"></i> Persetujuan SKP
    </a>
    <?php endif; ?>

    <?php if($role == 'super_admin'): ?>
    <a href="<?= base_url('/user') ?>">
        <i class="fas fa-users"></i> Manajemen User
    </a>
    <?php endif; ?>

    <a href="<?= base_url('/logout') ?>" style="color: #e74c3c; margin-top: 20px;">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>