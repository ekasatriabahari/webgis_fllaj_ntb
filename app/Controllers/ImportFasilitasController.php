<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Models\JenisFasilitasModel;
use CodeIgniter\HTTP\ResponseInterface;

class ImportFasilitasController extends BaseController
{
    public function import()
    {
        $fasilitasModel = new FasilitasModel();
        $jenisModel     = new JenisFasilitasModel();

        // --- Ambil JSON payload dari FormData ---
        $jsonString = $this->request->getPost('data');
        $payload = json_decode($jsonString, true);
        $log = [];

        if (!$payload || !is_array($payload)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data JSON tidak valid.'
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        // --- Ambil semua file foto yang dikirim lewat FormData ---
        $files = $this->request->getFiles();
        $imageFiles = isset($files['images']) ? $files['images'] : [];

        foreach ($payload as $index => $item) {
            try {
                // --- Cari ID jenis_fasilitas berdasarkan nama ---
                $jenis = $jenisModel
                    ->where('jenis', $item['jenis_fasilitas']['jenis_fasilitas'])
                    ->first();

                $jenisId = $jenis ? $jenis['id'] : null;

                // --- Generate kode fasilitas otomatis ---
                $prefix = strtoupper(substr($jenis ? $jenis['kode_fasilitas'] : 'UNK', 0, 3));
                $count  = $fasilitasModel->like('kode_fasilitas', $prefix, 'after')->countAllResults();
                $kode   = $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

                // --- Persiapan folder target upload ---
                $uploadedNames = [];
                $tahunSurvey   = $item['tahun_survey'];
                $targetDir     = FCPATH . "uploads/images/fasilitas/{$tahunSurvey}/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

                // --- Cocokkan nama foto dengan file yang dikirim ---
                foreach ($item['foto'] as $fotoName) {
                    foreach ($imageFiles as $file) {
                        if (basename($file->getName()) === $fotoName) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = time() . '_' . uniqid() . '.jpg';
                                $file->move($targetDir, $newName);

                                $imageService = \Config\Services::image()
                                    ->withFile($targetDir . $newName)
                                    ->resize(1024, 768, true, 'auto')
                                    ->save($targetDir . $newName);

                                $uploadedNames[] = $newName;
                            }
                        }
                    }
                }

                // --- Data yang akan disimpan ke DB ---
                $data = [
                    'kode_fasilitas'     => $kode,
                    'jenis_fasilitas_id' => $jenisId,
                    'nama_fasilitas'     => $item['nama_fasilitas'],
                    'kondisi'            => $item['kondisi'],
                    'latitude'           => $item['latitude'],
                    'longitude'          => $item['longitude'],
                    'tahun_survey'       => $tahunSurvey,
                    'catatan'            => $item['catatan'],
                    'foto'               => json_encode($uploadedNames),
                    'created_at'         => $item['created_at']
                ];

                // --- Simpan ke database ---
                if ($fasilitasModel->insert($data)) {
                    $log[] = [
                        'kode_fasilitas' => $kode,
                        'status'         => 'success',
                        'message'        => '✅ Data berhasil diimport'
                    ];
                } else {
                    $log[] = [
                        'kode_fasilitas' => $kode,
                        'status'         => 'error',
                        'message'        => '❌ Gagal menyimpan data ke database'
                    ];
                }

            } catch (\Throwable $e) {
                $log[] = [
                    'kode_fasilitas' => $item['kode_fasilitas'] ?? "AUTO-{$index}",
                    'status'         => 'error',
                    'message'        => '⚠️ ' . $e->getMessage()
                ];
            }
        }

        // --- Kembalikan hasil log ke frontend ---
        return $this->response->setJSON([
            'status' => 'completed',
            'total'  => count($payload),
            'log'    => $log
        ]);
    }
}
