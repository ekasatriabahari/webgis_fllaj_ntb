<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DatabaseController extends BaseController
{
    public function index()
    {
        $data['title'] = "<i class='fas fa-database'></i> Database";
        $data['content'] = "pages/database/index";
        return view ('layout/main', $data);
    }
}
