<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRincianNilaiToPenilaianSkp extends Migration
{
    public function up()
    {
        $this->forge->addColumn('penilaian_skp', [
            'rincian_nilai' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'predikat'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('penilaian_skp', 'rincian_nilai');
    }
}
