<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use App\Models\JenisFasilitasModel;
use App\Models\RencanaModel;
use CodeIgniter\HTTP\ResponseInterface;

class ImportFasilitasController extends BaseController
{
    public function import()
    {
        $fasilitasModel = new FasilitasModel();
        $jenisModel     = new JenisFasilitasModel();
        $rencanaModel   = new RencanaModel();
        $imageService   = \Config\Services::image();

        // Ambil JSON payload dari FormData
        $jsonString = $this->request->getPost('data');
        $payload = json_decode($jsonString, true);
        $log = [];

        if (!$payload) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data JSON tidak valid.'
            ], ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Jika hanya satu objek
        if (isset($payload['kode_fasilitas'])) {
            $payload = [$payload];
        }

        // Ambil semua file gambar
        $imageFiles = $this->request->getFileMultiple('images') ?? [];

        foreach ($payload as $index => $item) {
            try {
                // --- Cari jenis fasilitas ---
                $jenis = $jenisModel
                    ->where('jenis', $item['jenis_fasilitas']['jenis_fasilitas'])
                    ->first();

                $jenisId   = $jenis['id'] ?? null;
                $kodeJenis = $jenis['kode_fasilitas'] ?? 'UNK';
                $prefix    = strtoupper(substr($kodeJenis, 0, 3));
                $tahun     = $item['tahun_survey'] ?? date('Y');
                $rencana   = !empty($item['rencana']) ? filter_var($item['rencana'], FILTER_VALIDATE_BOOLEAN) : false;

                // --- Hitung urutan kode otomatis ---
                if ($rencana) {
                    $count = $rencanaModel->like('kode_fasilitas', 'RNC-' . $prefix, 'after')->countAllResults();
                    $kode  = 'RNC-' . $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $count = $fasilitasModel->like('kode_fasilitas', $prefix, 'after')->countAllResults();
                    $kode  = $prefix . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
                }

                // --- Folder Upload ---
                $targetDir = $rencana
                    ? FCPATH . "uploads/images/rencana/{$tahun}/"
                    : FCPATH . "uploads/images/fasilitas/{$tahun}/";

                if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);

                // --- Upload Foto ---
                $uploadedNames = [];
                foreach ($imageFiles as $file) {
                    $originalName = basename($file->getName());
                    if (in_array($originalName, $item['foto'])) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = time() . '_' . uniqid() . '.' . $file->getClientExtension();
                            $file->move($targetDir, $newName);

                            // Resize untuk efisiensi
                            try {
                                $imageService->withFile($targetDir . $newName)
                                    ->resize(800, 600, true, 'auto')
                                    ->save($targetDir . $newName);
                            } catch (\Throwable $e) {
                                // Abaikan jika resize gagal
                            }

                            $uploadedNames[] = $newName;
                        }
                    }
                }

                // --- Data yang akan disimpan ---
                $commonData = [
                    'kode_fasilitas'     => $kode,
                    'jenis_fasilitas_id' => $jenisId,
                    'nama_fasilitas'     => $item['nama_fasilitas'] ?? 'Tanpa nama',
                    'latitude'           => $item['latitude'],
                    'longitude'          => $item['longitude'],
                    'tahun_survey'       => $tahun,
                    'catatan'            => $item['catatan'] ?? '',
                    'foto'               => json_encode($uploadedNames),
                    'created_at'         => $item['created_at'] ?? date('Y-m-d H:i:s')
                ];

                // --- Simpan ke tabel yang sesuai ---
                if ($rencana) {
                    if ($rencanaModel->insert($commonData)) {
                        $log[] = [
                            'kode_fasilitas' => $kode,
                            'status'         => 'success',
                            'message'        => "✅ Data *rencana* berhasil diimport (" . count($uploadedNames) . " foto)"
                        ];
                    } else {
                        $log[] = [
                            'kode_fasilitas' => $kode,
                            'status'         => 'error',
                            'message'        => '❌ Gagal menyimpan data rencana ke database'
                        ];
                    }
                } else {
                    // Tambah kondisi hanya untuk fasilitas
                    $commonData['kondisi'] = $item['kondisi'] ?? 'baik';

                    if ($fasilitasModel->insert($commonData)) {
                        $log[] = [
                            'kode_fasilitas' => $kode,
                            'status'         => 'success',
                            'message'        => "✅ Data fasilitas berhasil diimport (" . count($uploadedNames) . " foto)"
                        ];
                    } else {
                        $log[] = [
                            'kode_fasilitas' => $kode,
                            'status'         => 'error',
                            'message'        => '❌ Gagal menyimpan data fasilitas ke database'
                        ];
                    }
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
