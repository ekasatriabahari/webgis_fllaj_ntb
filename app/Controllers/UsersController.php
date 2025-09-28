<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UsersController extends BaseController
{
    public function index()
    {
        //
    }

    public function profile()
    {
        $data['title'] = 'Profile';
        $data['content'] = 'pages/profile/index';
        return view('layout/main', $data);
    }

    public function detail($id)
    {
        $model = model('UsersModel');
        $data = $model->find($id);
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
                'data' => [],
                'http_code' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }
}
