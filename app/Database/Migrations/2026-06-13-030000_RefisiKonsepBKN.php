<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefisiKonsepBKN extends Migration
{
    public function up()
    {
        // Tambah tanggal_mulai dan tanggal_selesai ke skp_master
        $this->forge->addColumn('skp_master', [
            'tanggal_mulai' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'periode_id'
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tanggal_mulai'
            ],
        ]);

        // Ganti perspektif jadi aspek di rhk_indikator
        $this->forge->modifyColumn('rhk_indikator', [
            'perspektif' => [
                'name' => 'aspek',
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => null,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('skp_master', 'tanggal_mulai');
        $this->forge->dropColumn('skp_master', 'tanggal_selesai');
        $this->forge->modifyColumn('rhk_indikator', [
            'aspek' => [
                'name' => 'perspektif',
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => null,
            ],
        ]);
    }
}
