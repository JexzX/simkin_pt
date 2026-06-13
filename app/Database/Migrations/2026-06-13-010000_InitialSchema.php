<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialSchema extends Migration
{
    public function up()
    {
        // Add pendekatan column to skp_master
        $this->forge->addColumn('skp_master', [
            'pendekatan' => [
                'type' => 'ENUM',
                'constraint' => ['kuantitatif', 'kualitatif'],
                'default' => 'kuantitatif',
                'after' => 'periode_id'
            ]
        ]);

        // Add intervensi_dari_nama column to rhk
        $this->forge->addColumn('rhk', [
            'intervensi_dari_nama' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'intervensi_dari_id'
            ]
        ]);

        // Drop master_iksk table if exists
        $this->forge->dropTable('master_iksk', true);
        
        // Drop master_sk table if exists
        $this->forge->dropTable('master_sk', true);
        
        // Drop master_sp table if exists
        $this->forge->dropTable('master_sp', true);
    }

    public function down()
    {
        // Reverse: remove columns
        $this->forge->dropColumn('skp_master', 'pendekatan');
        $this->forge->dropColumn('rhk', 'intervensi_dari_nama');
    }
}
