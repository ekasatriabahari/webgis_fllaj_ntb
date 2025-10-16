<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogDatabase extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'type' => [ // backup atau import
                'type' => 'ENUM',
                'constraint' => ['backup', 'import'],
                'default' => 'backup'
            ],
            'filename' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('log_database');
    }

    public function down()
    {
        $this->forge->dropTable('log_database');
    }
}
