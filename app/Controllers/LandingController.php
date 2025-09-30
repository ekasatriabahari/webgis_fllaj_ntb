<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LandingController extends BaseController
{
    public function index()
    {
        return view('pages/landing/index');
    }

    public function kondisiFasilitas()
    {
        $model = model('FasilitasModel');
        $draw   = $this->request->getGet('draw');
        $start  = $this->request->getGet('start');
        $length = $this->request->getGet('length');

        // Parameter filter dari DataTables
        $filterKondisi = $this->request->getGet('columns')[4]['search']['value'];

        // Total seluruh data (tanpa filter)
        $totalRecords = $model->countAll();

        // Builder untuk data & filter
        $builder = $model->builder();

        if (!empty($filterKondisi)) {
            $builder->where('kondisi', $filterKondisi);
        }

        // ----- hitung jumlah setelah filter -----
        // clone builder agar kondisi where tetap sama
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        // ----- ambil data dengan limit & offset -----
        $builder->limit($length, $start);
        $data = $builder->join('jenis_fasilitas', 'fasilitas.jenis_fasilitas_id = jenis_fasilitas.id')
                ->select('fasilitas.*, jenis_fasilitas.kategori, jenis_fasilitas.jenis')->get()->getResultArray();

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data
        ]);
    }
}
