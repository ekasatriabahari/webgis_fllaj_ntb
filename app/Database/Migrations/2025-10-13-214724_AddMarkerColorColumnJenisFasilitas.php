<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMarkerColorColumnJenisFasilitas extends Migration
{
    public function up()
    {
        $fields = [
            'marker_color' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'deskripsi'
            ]
        ];

        $this->forge->addColumn('jenis_fasilitas', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('jenis_fasilitas', 'marker_color');
    }
}
