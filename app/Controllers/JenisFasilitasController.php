<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

class JenisFasilitasController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Jenis Fasilitas';
        $data['content'] = 'pages/jenis_fasilitas/index';
        return view('layout/main', $data);
    }

    public function getAll()
    {
        $jenis_fasilitas = model('JenisFasilitasModel');
        $draw   = $this->request->getGet('draw');
        $start  = $this->request->getGet('start');
        $length = $this->request->getGet('length');

        // Parameter filter dari DataTables
        $filterKategori = $this->request->getGet('columns')[1]['search']['value'];

        // Total seluruh data (tanpa filter)
        $totalRecords = $jenis_fasilitas->countAll();

        // Builder untuk data & filter
        $builder = $jenis_fasilitas->builder();

        if (!empty($filterKategori)) {
            $builder->where('kategori', $filterKategori);
        }

        // ----- hitung jumlah setelah filter -----
        // clone builder agar kondisi where tetap sama
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        // ----- ambil data dengan limit & offset -----
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data
        ]);
    }

    public function addData()
    {
        $data = $this->request->getPost();
        $data['created_at'] = date('Y-m-d H:i:s');

        // Mapping kategori → prefix
        $prefixMap = [
            'Rambu'          => 'RMB',
            'Marka'          => 'MRK',
            'Pagar Pengaman' => 'PGR',
            'Penanda Jalan'  => 'PND',
            'Penerangan'     => 'PNR',
            'Pemelandai'     => 'PML',
            'Lainnya'        => 'LNN',
        ];

        // Tambahkan prefix sesuai kategori yang diposting
        if (isset($data['kategori']) && isset($prefixMap[$data['kategori']])) {
            $data['kode_fasilitas'] = $prefixMap[$data['kategori']];
        } else {
            // Jika kategori tidak valid, kembalikan error
            return $this->response->setJSON([
                'status'    => 'error',
                'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
                'success'   => false,
                'message'   => 'Kategori tidak dikenal'
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        $jenis_fasilitas = model('JenisFasilitasModel');
        $saved = $jenis_fasilitas->insert($data);

        if ($saved) {
            $response = [
                'status'    => 'success',
                'http_code' => ResponseInterface::HTTP_OK,
                'success'   => true,
                'message'   => 'Data berhasil disimpan',
            ];
        } else {
            $response = [
                'status'    => 'error',
                'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
                'success'   => false,
                'message'   => 'Data gagal disimpan',
            ];
        }

        return $this->response->setJSON($response, $response['http_code']);
    }


    public function detail($id)
    {
        $jenis_fasilitas = model('JenisFasilitasModel');
        $data = $jenis_fasilitas->find($id);
        return $this->response->setJSON($data);
    }

    public function updateData()
    {
        $data = $this->request->getRawInputVar();
        $data['updated_at'] = date('Y-m-d H:i:s');
        $jenis_fasilitas = model('JenisFasilitasModel');
        $updated = $jenis_fasilitas->update($data['id'], $data);
        if ($updated) {
            $response = [
                'status' => 'success',
                'http_code' => ResponseInterface::HTTP_OK,
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ];
        } else {
            $response = [
                'status' => 'error',
                'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Data gagal disimpan',
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }

    public function deleteData($id)
    {
        $jenis_fasilitas = model('JenisFasilitasModel');
        $deleted = $jenis_fasilitas->delete($id);
        if ($deleted) {
            $response = [
                'status' => 'success',
                'http_code' => ResponseInterface::HTTP_OK,
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 'error',
                'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Data gagal dihapus',
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }
}
