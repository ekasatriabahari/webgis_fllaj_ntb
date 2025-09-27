<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class FasilitasController extends BaseController
{
    public function index()
    {
        //
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Fasilitas',
            // 'fasilitas' => $this->fasilitasModel->findAll(),
            // 'jenisfasilitas' => $this->jenisfasilitasModel->findAll(),
            'content' => 'pages/fasilitas/add',
        ];
        return view('layout/main', $data);
    }
}
