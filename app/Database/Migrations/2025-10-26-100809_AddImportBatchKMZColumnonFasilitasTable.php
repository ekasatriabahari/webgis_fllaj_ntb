<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImportBatchKMZColumnonFasilitasTable extends Migration
{
    public function up()
    {
        $field = [
            'import_batch_kmz' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'catatan'
            ],
        ];

        $this->forge->addColumn('fasilitas', $field);
        $this->forge->addColumn('rencana', $field);
    }

    public function down()
    {
        $this->forge->dropColumn('fasilitas', 'import_batch_kmz');
        $this->forge->dropColumn('rencana', 'import_batch_kmz');
    }
}
