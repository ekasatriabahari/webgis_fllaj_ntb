<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JenisFasilitasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // RAMBU LALU LINTAS
            ['kategori' => 'Rambu', 'jenis' => 'Rambu Larangan'],
            ['kategori' => 'Rambu', 'jenis' => 'Rambu Perintah'],
            ['kategori' => 'Rambu', 'jenis' => 'Rambu Peringatan'],
            ['kategori' => 'Rambu', 'jenis' => 'Rambu Petunjuk'],
            ['kategori' => 'Rambu', 'jenis' => 'Rambu Tambahan'],
            
            // MARKA JALAN
            ['kategori' => 'Marka', 'jenis' => 'Marka Membujur'],
            ['kategori' => 'Marka', 'jenis' => 'Marka Melintang'],
            ['kategori' => 'Marka', 'jenis' => 'Marka Serong'],
            ['kategori' => 'Marka', 'jenis' => 'Marka Lambang'],
            ['kategori' => 'Marka', 'jenis' => 'Zebra Cross'],
            
            // PAGAR PENGAMAN
            ['kategori' => 'Pagar Pengaman', 'jenis' => 'Guardrail'],
            ['kategori' => 'Pagar Pengaman', 'jenis' => 'Pembatas Jalan'],
            
            // DELINATOR & PATOK
            ['kategori' => 'Penanda Jalan', 'jenis' => 'Delinator'],
            ['kategori' => 'Penanda Jalan', 'jenis' => 'Patok Kilometer'],
            ['kategori' => 'Penanda Jalan', 'jenis' => 'Patok Pengarah'],
            ['kategori' => 'Penanda Jalan', 'jenis' => 'Pita Penggaduh'],
            
            // PENERANGAN JALAN
            ['kategori' => 'Penerangan', 'jenis' => 'Lampu PJU'],
            ['kategori' => 'Penerangan', 'jenis' => 'Lampu PJU Tenaga Surya'],
            ['kategori' => 'Penerangan', 'jenis' => 'Lampu Traffic Light'],
            
            // PEMELANDAI JALAN
            ['kategori' => 'Pemelandai', 'jenis' => 'Speed Bump'],
            ['kategori' => 'Pemelandai', 'jenis' => 'Speed Hump'],
            ['kategori' => 'Pemelandai', 'jenis' => 'Speed Table'],
            
            // FASILITAS LAINNYA
            ['kategori' => 'Lainnya', 'jenis' => 'Cermin Tikungan'],
            ['kategori' => 'Lainnya', 'jenis' => 'JPO (Jembatan Penyeberangan Orang)'],
            ['kategori' => 'Lainnya', 'jenis' => 'Zebra Cross'],
            ['kategori' => 'Lainnya', 'jenis' => 'Pulau Jalan'],
            ['kategori' => 'Lainnya', 'jenis' => 'Papan Reklame'],
        ];

        $this->db->table('jenis_fasilitas')->insertBatch($data);
    }
}