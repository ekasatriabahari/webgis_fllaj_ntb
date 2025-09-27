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
            'content' => 'pages/fasilitas/add',
        ];
        return view('layout/main', $data);
    }

    public function addData()
    {
        $fasilitasModel = model('FasilitasModel');

        // -------- Validasi input dasar (kecuali kode_fasilitas, karena akan dibuat otomatis)
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_fasilitas'        => 'required', // kirim prefix dari jenis_fasilitas (contoh: RMB)
            'jenis_fasilitas_id' => 'required|integer',
            'nama_fasilitas'     => 'required',
            'kondisi'            => 'required|in_list[baik,rusak_ringan,rusak_berat]',
            'latitude'           => 'required|decimal',
            'longitude'          => 'required|decimal',
            'tahun_survey'       => 'required|numeric|exact_length[4]',
            'foto.*'             => 'uploaded[foto]|is_image[foto]|max_size[foto,2048]',
        ]);

        if (! $validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $validation->getErrors()
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        // -------- Generate kode_fasilitas otomatis
        $prefix = $this->request->getPost('kode_fasilitas');

        // hitung berapa fasilitas yang sudah punya prefix sama
        $count = $fasilitasModel->like('kode_fasilitas', $prefix, 'after')->countAllResults();

        // nomor urut = jumlah + 1 , padding 4 digit (0001, 0002, dst)
        $kodeBaru = $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        // -------- Upload multiple foto
        $uploadedNames = [];
        $files = $this->request->getFiles();

        if (isset($files['foto']) && is_array($files['foto'])) {
            foreach ($files['foto'] as $file) {
                if ($file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/images/fasilitas/', $newName);
                    $uploadedNames[] = $newName;
                }
            }
        }

        // -------- Data untuk insert
        $data = [
            'kode_fasilitas'     => $kodeBaru,
            'jenis_fasilitas_id' => $this->request->getPost('jenis_fasilitas_id'),
            'nama_fasilitas'     => $this->request->getPost('nama_fasilitas'),
            'kondisi'            => $this->request->getPost('kondisi'),
            'latitude'           => $this->request->getPost('latitude'),
            'longitude'          => $this->request->getPost('longitude'),
            'tahun_survey'       => $this->request->getPost('tahun_survey'),
            'catatan'            => $this->request->getPost('catatan'),
            'foto'               => json_encode($uploadedNames),
        ];

        // -------- Simpan ke DB
        if (! $fasilitasModel->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'success' => false,
                'message' => 'Gagal menyimpan data fasilitas'
            ], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'success' => true,
            'message' => 'Data fasilitas berhasil disimpan',
            'data'    => $data
        ], ResponseInterface::HTTP_CREATED);
    }
}
