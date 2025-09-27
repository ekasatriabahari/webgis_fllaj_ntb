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
        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        
        // Get filter parameter
        $filterKategori = $this->request->getGet('filterKategori');
        
        // Build query dengan filter
        $builder = $jenis_fasilitas->builder();
        
        // Jika ada filter kategori
        if (!empty($filterKategori)) {
            $builder->where('kategori', $filterKategori);
        }
        
        // Get total records tanpa filter
        $totalRecords = $jenis_fasilitas->countAll();
        
        // Get filtered data
        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();
        
        // Get total filtered records
        if (!empty($filterKategori)) {
            $filteredRecords = $jenis_fasilitas->where('kategori', $filterKategori)->countAllResults();
        } else {
            $filteredRecords = $totalRecords;
        }
        
        if ($data) {
            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ], 200);
        } else {
            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 200);
        }          
    }
}
