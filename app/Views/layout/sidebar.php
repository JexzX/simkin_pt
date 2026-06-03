<div class="sidebar">
    <div class="p-4 text-center text-white border-bottom border-secondary">
        <i class="fas fa-chart-line fa-2x mb-2"></i>
        <h5 class="mb-0">SIMKIN UIN</h5>
        <small>Salatiga</small>
    </div>
    <ul class="nav flex-column p-3">
        <!-- DEBUG: Cek role session -->
        <?php $userRole = session()->get('role'); ?>
        <!-- ROLE: <?= $userRole ?> -->
        
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        
        <?php if(in_array($userRole, ['super_admin', 'admin_perencana', 'rektor', 'dekan', 'kaprodi', 'dosen'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'skp' ? 'active' : '' ?>" href="<?= base_url('/skp') ?>">
                <i class="fas fa-file-alt"></i> SKP / RHK
            </a>
        </li>
        <?php endif; ?>
        
        <?php if(in_array($userRole, ['rektor', 'dekan', 'kaprodi', 'super_admin'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'approval' ? 'active' : '' ?>" href="<?= base_url('/approval/skp') ?>">
                <i class="fas fa-check-double"></i> Persetujuan SKP
            </a>
        </li>
        <?php endif; ?>
        
        <?php if(in_array($userRole, ['dosen', 'staff', 'kaprodi', 'dekan', 'rektor'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'realisasi' ? 'active' : '' ?>" href="<?= base_url('/realisasi') ?>">
                <i class="fas fa-chart-line"></i> Realisasi
            </a>
        </li>
        <?php endif; ?>
        
        <?php if(in_array($userRole, ['kaprodi', 'dekan', 'rektor'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'approval_realisasi' ? 'active' : '' ?>" href="<?= base_url('/realisasi/approval') ?>">
                <i class="fas fa-clipboard-list"></i> Persetujuan Realisasi
            </a>
        </li>
        <?php endif; ?>
        
        <?php if(in_array($userRole, ['rektor', 'dekan', 'kaprodi'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'penilaian' ? 'active' : '' ?>" href="<?= base_url('/penilaian') ?>">
                <i class="fas fa-star"></i> Penilaian SKP
            </a>
        </li>
        <?php endif; ?>
        
        <?php if(in_array($userRole, ['super_admin', 'admin_perencana'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'master' ? 'active' : '' ?>" href="#masterMenu" data-bs-toggle="collapse">
                <i class="fas fa-database"></i> Master Data <i class="fas fa-chevron-down float-end"></i>
            </a>
            <div class="collapse <?= ($active_menu ?? '') == 'master' ? 'show' : '' ?>" id="masterMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/master/sp') ?>">Sasaran Program (SP)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/master/sk') ?>">Sasaran Kegiatan (SK)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/master/iksk') ?>">Indikator SK (IKSK)</a>
                    </li>
                </ul>
            </div>
        </li>
        <?php endif; ?>
        
        <?php if($userRole == 'super_admin'): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'user' ? 'active' : '' ?>" href="<?= base_url('/user') ?>">
                <i class="fas fa-users"></i> Manajemen User
            </a>
        </li>
        <?php endif; ?>
        
        <?php if(in_array($userRole, ['super_admin', 'admin_perencana', 'rektor'])): ?>
        <li class="nav-item">
            <a class="nav-link <?= ($active_menu ?? '') == 'laporan' ? 'active' : '' ?>" href="#laporanMenu" data-bs-toggle="collapse">
                <i class="fas fa-print"></i> Laporan <i class="fas fa-chevron-down float-end"></i>
            </a>
            <div class="collapse <?= ($active_menu ?? '') == 'laporan' ? 'show' : '' ?>" id="laporanMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/laporan/skp') ?>">Laporan SKP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/laporan/realisasi') ?>">Laporan Realisasi</a>
                    </li>
                </ul>
            </div>
        </li>
        <?php endif; ?>
    </ul>
</div>