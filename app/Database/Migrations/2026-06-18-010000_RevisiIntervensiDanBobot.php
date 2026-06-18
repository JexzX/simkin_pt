<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RevisiIntervensiDanBobot extends Migration
{
    public function up()
    {
        // 1. Tambah kolom intervensi_dari_manual di rhk (untuk rektor)
        $this->forge->addColumn('rhk', [
            'intervensi_dari_manual' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
                'after' => 'intervensi_dari_id'
            ]
        ]);

        // 2. Tabel pivot: RHK <> Indikator Atasan yang diintervensi
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'rhk_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'indikator_atasan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('rhk_id', 'rhk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('indikator_atasan_id', 'rhk_indikator', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rhk_intervensi_indikator');
    }

    public function down()
    {
        $this->forge->dropTable('rhk_intervensi_indikator');
        $this->forge->dropColumn('rhk', 'intervensi_dari_manual');
    }
}
