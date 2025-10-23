<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LogImportKmzController extends BaseController
{
    public function save()
    {
        $filepath = $this->request->getPost('filepath');
        $username = session('username');

        if (!$filepath) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Path folder tidak boleh kosong.'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $model = model('LogImportKmzModel');
        $model->insert([
            'filepath' => $filepath,
            'created_by' => $username ?? 'unknown',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'success' => true,
            'message' => 'Log import berhasil disimpan.'
        ]);
    }

    public function lists()
    {
        $model = model('LogImportKmzModel');
        $lists = $model->orderBy('created_at', 'DESC')->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'success' => true,
            'data' => $lists ?? []
        ]);
    }
}
