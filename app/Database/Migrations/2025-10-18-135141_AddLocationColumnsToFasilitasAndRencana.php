<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationColumnsToFasilitasAndRencana extends Migration
{
    public function up()
    {
        // === Tabel fasilitas ===
        $this->forge->addColumn('fasilitas', [
            'nama_ruas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'foto'
            ],
            'kelurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'nama_ruas'
            ],
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'kelurahan'
            ],
            'kab_kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'kecamatan'
            ],
        ]);

        // === Tabel rencana ===
        $this->forge->addColumn('rencana', [
            'nama_ruas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'foto'
            ],
            'kelurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'nama_ruas'
            ],
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'kelurahan'
            ],
            'kab_kota' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'kecamatan'
            ],
        ]);
    }

    public function down()
    {
        // Hapus kolom jika rollback
        $this->forge->dropColumn('fasilitas', ['nama_ruas', 'kelurahan', 'kecamatan', 'kab_kota']);
        $this->forge->dropColumn('rencana', ['nama_ruas', 'kelurahan', 'kecamatan', 'kab_kota']);
    }
}
