<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class ChartsController extends BaseController
{
    public function kondisi()
    {
        $fasilitasModel = model('FasilitasModel');

        // Hitung jumlah tiap kondisi
        $baik = $fasilitasModel->where('kondisi', 'baik')->countAllResults();
        $rusakRingan = $fasilitasModel->where('kondisi', 'rusak_ringan')->countAllResults();
        $rusakBerat = $fasilitasModel->where('kondisi', 'rusak_berat')->countAllResults();

        $total = $baik + $rusakRingan + $rusakBerat;

        // Hindari pembagian 0
        if ($total == 0) {
            $data = [
                ['name' => 'Baik', 'y' => 0],
                ['name' => 'Rusak Ringan', 'y' => 0],
                ['name' => 'Rusak Berat', 'y' => 0],
            ];
        } else {
            $data = [
                ['name' => 'Baik', 'y' => round(($baik / $total) * 100, 2)],
                ['name' => 'Rusak Ringan', 'y' => round(($rusakRingan / $total) * 100, 2)],
                ['name' => 'Rusak Berat', 'y' => round(($rusakBerat / $total) * 100, 2)],
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function eksistingRencana()
    {
        $fasilitasModel = model('FasilitasModel');
        $rencanaModel = model('RencanaModel');

        $jumlahFasilitas = $fasilitasModel->countAllResults();
        $jumlahRencana   = $rencanaModel->countAllResults();

        $total = $jumlahFasilitas + $jumlahRencana;

        if ($total == 0) {
            $data = [
                ['name' => 'Eksisting', 'y' => 0],
                ['name' => 'Rencana', 'y' => 0],
            ];
        } else {
            $data = [
                [
                    'name' => 'Eksisting',
                    'y' => round(($jumlahFasilitas / $total) * 100, 2)
                ],
                [
                    'name' => 'Rencana',
                    'y' => round(($jumlahRencana / $total) * 100, 2)
                ]
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function fasilitasRencanaPerKabKota()
    {
        $db = Database::connect();

        $builder1 = $db->table('fasilitas');
        $builder1->select('kab_kota, COUNT(*) as total');
        $builder1->groupBy('kab_kota');
        $builder1->orderBy('kab_kota', 'ASC');
        $fasilitasData = $builder1->get()->getResultArray();

        $builder2 = $db->table('rencana');
        $builder2->select('kab_kota, COUNT(*) as total');
        $builder2->groupBy('kab_kota');
        $builder2->orderBy('kab_kota', 'ASC');
        $rencanaData = $builder2->get()->getResultArray();

        $allKabKota = [];

        foreach ($fasilitasData as $row) {
            $allKabKota[$row['kab_kota']]['fasilitas'] = (int)$row['total'];
        }

        foreach ($rencanaData as $row) {
            $allKabKota[$row['kab_kota']]['rencana'] = (int)$row['total'];
        }

        $categories = [];
        $fasilitasValues = [];
        $rencanaValues = [];

        foreach ($allKabKota as $kab => $data) {
            $categories[] = $kab;
            $fasilitasValues[] = isset($data['fasilitas']) ? $data['fasilitas'] : 0;
            $rencanaValues[] = isset($data['rencana']) ? $data['rencana'] : 0;
        }

        return $this->response->setJSON([
            'status' => 'success',
            'categories' => $categories,
            'series' => [
                ['name' => 'Fasilitas', 'data' => $fasilitasValues],
                ['name' => 'Rencana', 'data' => $rencanaValues],
            ]
        ]);
    }
}
