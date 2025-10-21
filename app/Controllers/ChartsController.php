<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

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
                ['name' => 'Realisasi', 'y' => 0],
                ['name' => 'Rencana', 'y' => 0],
            ];
        } else {
            $data = [
                [
                    'name' => 'Realisasi',
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
}
