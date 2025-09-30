<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['content'] = 'pages/dashboard/index';
        return view('layout/main', $data);
    }

    public function getDataMarkers()
{
    $fasilitas = model('FasilitasModel');
    $rows = $fasilitas
        ->join('jenis_fasilitas', 'fasilitas.jenis_fasilitas_id = jenis_fasilitas.id')
        ->select('fasilitas.*, jenis_fasilitas.jenis, jenis_fasilitas.kode_fasilitas AS kode, jenis_fasilitas.kategori, jenis_fasilitas.icon')
        ->get()
        ->getResultArray();

    if ($rows) {
        // --- grouping ---
        $grouped = [];
        foreach ($rows as $row) {
            $kode = $row['kode'];
            $kategori = $row['kategori'];
            $jenis = $row['jenis'];

            if (!isset($grouped[$kode])) {
                $grouped[$kode] = [
                    'kode' => $kode,
                    'nama_kode' => $kategori,
                    'jenis' => []
                ];
            }

            if (!isset($grouped[$kode]['jenis'][$jenis])) {
                $grouped[$kode]['jenis'][$jenis] = [
                    'nama_jenis' => $jenis,
                    'icon' => $row['icon'],
                    'data' => []
                ];
            }

            $grouped[$kode]['jenis'][$jenis]['data'][] = [
                'id' => $row['id'],
                'kode_fasilitas' => $row['kode_fasilitas'],
                'nama_fasilitas' => $row['nama_fasilitas'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'kondisi' => $row['kondisi'],
                'foto' => $row['foto'],
            ];
        }

        // ubah associative array jenis jadi array numerik
        $finalData = array_values(array_map(function ($item) {
            $item['jenis'] = array_values($item['jenis']);
            return $item;
        }, $grouped));

        $response = [
            'status' => 'success',
            'success' => true,
            'data' => $finalData,
            'http_code' => ResponseInterface::HTTP_OK,
        ];
    } else {
        $response = [
            'status' => 'error',
            'success' => false,
            'data' => 'Data fasilitas tidak ditemukan',
            'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
        ];
    }

    return $this->response->setJSON($response, $response['http_code']);
}

}
