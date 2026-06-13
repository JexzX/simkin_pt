<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupRolesAndStatus extends Migration
{
    public function up()
    {
        $this->db->query("UPDATE skp_master SET status = 'pengajuan' WHERE status = 'menunggu_approval'");
        $this->db->query("UPDATE skp_master SET status = 'selesai' WHERE status = 'dinilai'");

        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin_perencana','rektor','dekan','kaprodi','dosen') NOT NULL");

        $this->db->query("ALTER TABLE skp_master MODIFY COLUMN status ENUM('draft','pengajuan','disetujui','ditolak','selesai') DEFAULT 'draft'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin_perencana','rektor','wakil_rektor','dekan','wakil_dekan','kaprodi','sekprodi','dosen','staff') NOT NULL");
        $this->db->query("ALTER TABLE skp_master MODIFY COLUMN status ENUM('draft','menunggu_approval','disetujui','ditolak','dinilai','selesai') DEFAULT 'draft'");
    }
}
