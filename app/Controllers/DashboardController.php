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
        $rencana = model('RencanaModel');

        // --- Ambil data fasilitas + join jenis_fasilitas ---
        $rowsFasilitas = $fasilitas
            ->join('jenis_fasilitas', 'fasilitas.jenis_fasilitas_id = jenis_fasilitas.id')
            ->select('fasilitas.*, jenis_fasilitas.jenis, jenis_fasilitas.kode_fasilitas AS kode, jenis_fasilitas.kategori, jenis_fasilitas.icon, jenis_fasilitas.marker_color')
            ->get()
            ->getResultArray();

        // --- Ambil data rencana ---
        $rowsRencana = $rencana
            ->join('jenis_fasilitas', 'rencana.jenis_fasilitas_id = jenis_fasilitas.id')
            ->select('rencana.*, jenis_fasilitas.jenis, jenis_fasilitas.kode_fasilitas AS kode, jenis_fasilitas.kategori, jenis_fasilitas.icon, jenis_fasilitas.marker_color')
            ->get()
            ->getResultArray();

        // --- Proses grouping fasilitas seperti semula ---
        $groupedFasilitas = [];
        if ($rowsFasilitas) {
            foreach ($rowsFasilitas as $row) {
                $kode = $row['kode'];
                $kategori = $row['kategori'];
                $jenis = $row['jenis'];

                if (!isset($groupedFasilitas[$kode])) {
                    $groupedFasilitas[$kode] = [
                        'kode' => $kode,
                        'nama_kode' => $kategori,
                        'jenis' => []
                    ];
                }

                if (!isset($groupedFasilitas[$kode]['jenis'][$jenis])) {
                    $groupedFasilitas[$kode]['jenis'][$jenis] = [
                        'nama_jenis' => $jenis,
                        'icon' => $row['icon'],
                        'marker_color' => $row['marker_color'],
                        'data' => []
                    ];
                }

                $groupedFasilitas[$kode]['jenis'][$jenis]['data'][] = [
                    'id' => $row['id'],
                    'kode_fasilitas' => $row['kode_fasilitas'],
                    'nama_fasilitas' => $row['nama_fasilitas'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'kondisi' => $row['kondisi'],
                    'catatan' => $row['catatan'],
                    'tahun_survey' => $row['tahun_survey'],
                    'foto' => $row['foto'],
                    'lokasi' => $row['nama_ruas'] . ', ' . $row['kelurahan'] . ', ' . $row['kecamatan'] . ', ' . $row['kab_kota'],
                ];
            }
        }

        // ubah associative array jenis jadi array numerik
        $finalFasilitas = array_values(array_map(function ($item) {
            $item['jenis'] = array_values($item['jenis']);
            return $item;
        }, $groupedFasilitas));

        // --- Proses grouping rencana seperti semula ---
        $groupedRencana = [];
        if ($rowsRencana) {
            foreach ($rowsRencana as $row) {
                $kode = $row['kode'];
                $kategori = $row['kategori'];
                $jenis = $row['jenis'];

                if (!isset($groupedRencana[$kode])) {
                    $groupedRencana[$kode] = [
                        'kode' => $kode,
                        'nama_kode' => $kategori,
                        'jenis' => []
                    ];
                }

                if (!isset($groupedRencana[$kode]['jenis'][$jenis])) {
                    $groupedRencana[$kode]['jenis'][$jenis] = [
                        'nama_jenis' => $jenis,
                        'icon' => $row['icon'],
                        'marker_color' => $row['marker_color'],
                        'data' => []
                    ];
                }

                $groupedRencana[$kode]['jenis'][$jenis]['data'][] = [
                    'id' => $row['id'],
                    'kode_fasilitas' => $row['kode_fasilitas'],
                    'nama_fasilitas' => $row['nama_fasilitas'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'catatan' => $row['catatan'],
                    'tahun_survey' => $row['tahun_survey'],
                    'foto' => $row['foto'],
                    'lokasi' => $row['nama_ruas'] . ', ' . $row['kelurahan'] . ', ' . $row['kecamatan'] . ', ' . $row['kab_kota'],
                ];
            }
        }

        // ubah associative array jenis jadi array numerik
        $finalRencana = array_values(array_map(function ($item) {
            $item['jenis'] = array_values($item['jenis']);
            return $item;
        }, $groupedRencana));

        // --- Buat response ---
        if (!empty($finalFasilitas) || !empty($rowsRencana)) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => [
                    'fasilitas' => $finalFasilitas,
                    'rencana' => $finalRencana
                ],
                'http_code' => ResponseInterface::HTTP_OK,
            ];
        } else {
            $response = [
                'status' => 'error',
                'success' => false,
                'data' => [
                    'fasilitas' => [],
                    'rencana' => []
                ],
                'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
            ];
        }

        return $this->response->setJSON($response, $response['http_code']);
    }


}
