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
        $data = $fasilitas->join('jenis_fasilitas', 'fasilitas.jenis_fasilitas_id = jenis_fasilitas.id')
                ->select('fasilitas.*, jenis_fasilitas.jenis, jenis_fasilitas.icon')->get()->getResultArray();
        
        if ($data) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => $data,
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
