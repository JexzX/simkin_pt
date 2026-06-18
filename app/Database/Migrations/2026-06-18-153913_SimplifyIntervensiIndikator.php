<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SimplifyIntervensiIndikator extends Migration
{
    public function up()
    {
        // Add intervensi_indikator_id to rhk
        $this->forge->addColumn('rhk', [
            'intervensi_indikator_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'intervensi_dari_manual'
            ]
        ]);

        // Migrate existing data: copy first indikator from pivot table per rhk
        $db = \Config\Database::connect();
        $pivotRows = $db->table('rhk_intervensi_indikator')
            ->select('rhk_id, indikator_atasan_id')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($pivotRows as $row) {
            if (!isset($grouped[$row['rhk_id']])) {
                $grouped[$row['rhk_id']] = $row['indikator_atasan_id'];
            }
        }
        foreach ($grouped as $rhkId => $indId) {
            $db->table('rhk')->where('id', $rhkId)->update(['intervensi_indikator_id' => $indId]);
        }

        // Drop pivot table
        $this->forge->dropTable('rhk_intervensi_indikator', true);

        // Drop unused columns from rhk
        $this->forge->dropColumn('rhk', ['intervensi_dari_type', 'intervensi_dari_id', 'intervensi_dari_nama', 'bobot']);
    }

    public function down()
    {
        $this->forge->dropColumn('rhk', 'intervensi_indikator_id');

        $this->forge->addColumn('rhk', [
            'intervensi_dari_type' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'intervensi_dari_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'intervensi_dari_nama' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bobot' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ]);

        $this->forge->createTable('rhk_intervensi_indikator');
        $this->forge->addColumn('rhk_intervensi_indikator', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'rhk_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'indikator_atasan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey('id');
    }
}
