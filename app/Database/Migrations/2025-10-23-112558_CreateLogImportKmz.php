<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogImportKmz extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'filepath' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('log_import_kmz', true);
    }

    public function down()
    {
        $this->forge->dropTable('log_import_kmz', true);
    }
}
