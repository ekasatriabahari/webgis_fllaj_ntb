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
        $imageService   = \Config\Services::image();

        // Ambil JSON payload dari FormData
        $jsonString = $this->request->getPost('data');
        $payload = json_decode($jsonString, true);
        $log = [];

        // Tangani jika bukan array (mungkin hanya 1 item)
        if (!$payload) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data JSON tidak valid.'
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }
        if (isset($payload['kode_fasilitas'])) {
            $payload = [$payload];
        }

        // Ambil semua file foto dari FormData
        $imageFiles = $this->request->getFileMultiple('images') ?? [];

        foreach ($payload as $index => $item) {
            try {
                // Cari ID jenis_fasilitas
                $jenis = $jenisModel
                    ->where('jenis', $item['jenis_fasilitas']['jenis_fasilitas'])
                    ->first();

                $jenisId = $jenis['id'] ?? null;
                $kodeJenis = $jenis['kode_fasilitas'] ?? 'UNK';

                // Generate kode fasilitas otomatis
                $prefix = strtoupper(substr($kodeJenis, 0, 3));
                $count  = $fasilitasModel->like('kode_fasilitas', $prefix, 'after')->countAllResults();
                $kode   = $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

                // Siapkan folder upload
                $uploadedNames = [];
                $tahunSurvey   = $item['tahun_survey'];
                $targetDir     = FCPATH . "uploads/images/fasilitas/{$tahunSurvey}/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

                // Upload foto yang sesuai
                foreach ($imageFiles as $file) {
                    $originalName = basename($file->getName());
                    if (in_array($originalName, $item['foto'])) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = time() . '_' . uniqid() . '.' . $file->getClientExtension();
                            $file->move($targetDir, $newName);

                            try {
                                $imageService->withFile($targetDir . $newName)
                                            ->resize(800, 600, true, 'auto')
                                            ->save($targetDir . $newName);
                            } catch (\Throwable $e) {
                                // Abaikan error resize
                            }

                            $uploadedNames[] = $newName;
                        }
                    }
                }

                // Data simpan
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
                        'message'        => '✅ Data berhasil diimport (' . count($uploadedNames) . ' foto)'
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
