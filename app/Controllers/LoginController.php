<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $usersModel = model('UsersModel');
        $user = $usersModel
                ->where('username', $username)
                ->where('is_active', '1')->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'success' => false,
                'message' => 'Akun tidak ditemukan.'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'success' => false,
                'message' => 'Password salah.'
            ]);
        }

        if ($user['is_active'] !== '1') {
            return $this->response->setJSON([
                'status' => 'error',
                'success' => false,
                'message' => 'User tidak aktif.'
            ]);
        }

        // Set session
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'username'        => $user['username'],
            'nama'    => $user['nama'],
            'role'  => $user['role'],
        ]);

        return $this->response->setJSON(
            [
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => site_url('dashboard'),
            ],
        );
    }

    public function logout()
    {
        session()->destroy();
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(
                [
                    'success' => true,
                    'message' => 'Logout berhasil',
                    'redirect' => site_url('login'),
                ]
            );
        }
        return redirect()->to('/login');
    }
}
