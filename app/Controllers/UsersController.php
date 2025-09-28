<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UsersController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Daftar Users';
        $data['content'] = 'pages/users/index';
        return view('layout/main', $data);
    }

    public function profile()
    {
        $data['title'] = 'Profile';
        $data['content'] = 'pages/profile/index';
        return view('layout/main', $data);
    }

    public function getAll()
    {
        $draw = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search = $_REQUEST['search']['value'];

        $usersModel = model('UsersModel');
        $users = $usersModel
            ->like('users.username', $search)
            ->orLike('users.nama', $search)
            ->orderBy('users.created_at', 'DESC')
            ->findAll($length, $start);

        $data = [];
        foreach ($users as $key => $user) {
            $data[] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'nama' => $user['nama'],
                'role' => $user['role'],
                'is_active' => $user['is_active'] === '1' ? true : false,
                'created_at' => $user['created_at'],
            ];
        }

        $output = [
            'draw' => $draw,
            'recordsTotal' => $usersModel->countAll(),
            'recordsFiltered' => $usersModel->countAllResults($search),
            'data' => $data,
        ];
        return $this->response->setJSON($output);
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

    public function addData()
    {
        $model = model('UsersModel');
        $data = $this->request->getPost();
        $data['password'] = password_hash('12345678', PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $save = $model->insert($data);
        if ($save) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => 'User berhasil ditambahkan',
                'http_code' => ResponseInterface::HTTP_OK,
            ];
        } else {
            $response = [
                'status' => 'error',
                'success' => false,
                'data' => 'Internal Server Error',
                'http_code' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }

    public function updateData($id)
    {
        $model = model('UsersModel');
        $data = $this->request->getRawInput();
        $data['updated_at'] = date('Y-m-d H:i:s');
        $save = $model->update($id, $data);
        if ($save) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => 'User berhasil diubah',
                'http_code' => ResponseInterface::HTTP_OK,
            ];
        } else {
            $response = [
                'status' => 'error',
                'success' => false,
                'data' => 'Internal Server Error',
                'http_code' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }

    public function deleteData($id)
    {
        $model = model('UsersModel');
        $deleted = $model->delete($id);
        if($deleted) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => 'User berhasil dihapus',
                'http_code' => ResponseInterface::HTTP_OK,
            ];
        } else {
            $response = [
                'status' => 'error',
                'success' => false,
                'data' => 'Internal Server Error',
                'http_code' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }

    public function resetPassword()
    {
        $userId = $this->request->getPost('id');
        $model = model('UsersModel');
        $data = $model->find($userId);
        $data['password'] = password_hash('12345678', PASSWORD_DEFAULT);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $update = $model->update($userId, $data);
        if ($update) {
            $response = [
                'status' => 'success',
                'success' => true,
                'data' => 'Password berhasil direset',
                'http_code' => ResponseInterface::HTTP_OK,
            ];
        } else {
            $response = [
                'status' => 'error',
                'success' => false,
                'data' => 'Internal Server Error',
                'http_code' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
        return $this->response->setJSON($response, $response['http_code']);
    }
}
