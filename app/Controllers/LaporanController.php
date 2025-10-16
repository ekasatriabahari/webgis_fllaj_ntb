<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LaporanController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Laporan';
        $data['content'] = 'pages/laporan/index';
        return view('layout/main', $data);
    }
}
