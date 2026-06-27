<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterUnitTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_unit' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nama_unit');
        $this->forge->createTable('master_unit');
    }

    public function down()
    {
        $this->forge->dropTable('master_unit');
    }
}
