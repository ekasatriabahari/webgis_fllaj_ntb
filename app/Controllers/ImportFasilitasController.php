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

        $jsonString = $this->request->getPost('data');
        $payload = json_decode($jsonString, true);
        $log = [];

        if (!$payload || !is_array($payload)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data JSON tidak valid.'
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        foreach ($payload as $index => $item) {
            try {
                // --- Cari id jenis_fasilitas berdasarkan nama ---
                $jenis = $jenisModel
                    ->where('jenis', $item['jenis_fasilitas']['jenis_fasilitas'])
                    ->first();

                $jenisId = $jenis ? $jenis['id'] : null;

                // --- Generate kode fasilitas otomatis ---
                $prefix = strtoupper(substr($jenis ? $jenis['kode_fasilitas'] : 'UNK', 0, 3));
                $count  = $fasilitasModel->like('kode_fasilitas', $prefix, 'after')->countAllResults();
                $kode   = $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

                // --- Proses upload foto dari folder local (client) ---
                $uploadedNames = [];
                $tahunSurvey   = $item['tahun_survey'];
                $targetDir     = FCPATH . "uploads/images/fasilitas/{$tahunSurvey}/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

                $sourceDir = $this->request->getGet('source_path'); 
                // misal dikirim via query ?source_path=/mnt/data/data_survey

                if (is_dir($sourceDir . '/images')) {
                    foreach ($item['foto'] as $fotoName) {
                        $sourceFile = $sourceDir . '/images/' . $fotoName;
                        if (!is_file($sourceFile)) continue;

                        $newName = time() . '_' . uniqid() . '.jpg';
                        $targetFile = $targetDir . $newName;

                        // --- Resize dan simpan ---
                        $imageService = \Config\Services::image()
                            ->withFile($sourceFile)
                            ->resize(1024, 768, true, 'auto')
                            ->save($targetFile);

                        if (is_file($targetFile)) {
                            $uploadedNames[] = $newName;
                        }
                    }
                }

                // --- Data untuk simpan ke DB ---
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

        return $this->response->setJSON([
            'status' => 'completed',
            'total'  => count($payload),
            'log'    => $log
        ]);
    }
}
