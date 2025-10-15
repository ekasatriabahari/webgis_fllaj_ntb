<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RencanaController extends BaseController
{
    public function index()
    {   
        $data['title'] = 'Daftar Rencana';
        $data['content'] = 'pages/rencana/index';
        return view('layout/main', $data);
    }

    public function getAll()
    {
        $model = model('RencanaModel');
        $draw   = $this->request->getGet('draw');
        $start  = $this->request->getGet('start');
        $length = $this->request->getGet('length');

        // Parameter filter dari DataTables
        // $filterKategori = $this->request->getGet('columns')[1]['search']['value'];

        // Total seluruh data (tanpa filter)
        $totalRecords = $model->countAll();

        // Builder untuk data & filter
        $builder = $model->builder();

        // if (!empty($filterKategori)) {
        //     $builder->where('kategori', $filterKategori);
        // }

        // ----- hitung jumlah setelah filter -----
        // clone builder agar kondisi where tetap sama
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        // ----- ambil data dengan limit & offset -----
        $builder->limit($length, $start);
        $data = $builder->join('jenis_fasilitas', 'rencana.jenis_fasilitas_id = jenis_fasilitas.id')
                ->select('rencana.*, jenis_fasilitas.kategori, jenis_fasilitas.jenis')->get()->getResultArray();

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data
        ]);
    }

    public function detail($id)
    {
        $model = model('RencanaModel');
        $data = $model->find($id);
        $response = [
            'status' => 'success', 
            'success' => true,
            'data' => $data,
            'http_code' => ResponseInterface::HTTP_OK
        ];
        return $this->response->setJSON($response, $response['http_code']);
    }

    public function deleteData($id)
    {
        $model = model('RencanaModel');
        $data = $model->find($id);
        $images = $data['foto'];
        $decodedImages = json_decode($images, true);
        foreach ($decodedImages as $image) {
            unlink(FCPATH . 'uploads/images/rencana/' . $data['tahun_survey'] . '/' . $image);
        }
        $deleted = $model->delete($id);
        if ($deleted) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => 'Data rencana berhasil dihapus',
                'http_code' => ResponseInterface::HTTP_OK,
            ];
        } else {
            $response = [
                'status' => 'error',
                'success' => false,
                'data' => 'Data rencana gagal dihapus',
                'http_code' => ResponseInterface::HTTP_BAD_REQUEST,
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }
}
