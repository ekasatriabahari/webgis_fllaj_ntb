<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;

class DatabaseController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $data['title'] = "<i class='fas fa-database'></i> Database";
        $data['content'] = "pages/database/index";
        return view('layout/main', $data);
    }

    // =====================================
    // === BACKUP DATABASE (EXPORT .SQL) ===
    // =====================================
    public function backup()
    {
        helper('filesystem');

        $dbName = $this->db->database;
        $timestamp = date('Ymd_His');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        $backupDir = FCPATH . 'backups/';
        $filePath = $backupDir . $filename;

        // Pastikan direktori backup tersedia
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }

        $tables = $this->db->listTables();
        $sqlScript = "-- ===================================================\n";
        $sqlScript .= "-- DATABASE BACKUP: {$dbName}\n";
        $sqlScript .= "-- Created: " . date('Y-m-d H:i:s') . "\n";
        $sqlScript .= "-- ===================================================\n\n";
        $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Struktur tabel
            $createTableQuery = $this->db->query("SHOW CREATE TABLE `$table`")->getRowArray();
            $createSQL = $createTableQuery['Create Table'] ?? '';

            $sqlScript .= "-- ----------------------------\n";
            $sqlScript .= "-- Struktur tabel `$table`\n";
            $sqlScript .= "-- ----------------------------\n";
            $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
            $sqlScript .= $createSQL . ";\n\n";

            // Data tabel
            $query = $this->db->query("SELECT * FROM `$table`");
            $rows = $query->getResultArray();

            if (count($rows) > 0) {
                $sqlScript .= "-- ----------------------------\n";
                $sqlScript .= "-- Data untuk tabel `$table`\n";
                $sqlScript .= "-- ----------------------------\n";

                foreach ($rows as $row) {
                    $escapedValues = [];
                    foreach ($row as $val) {
                        $escapedValues[] = $this->db->escape($val);
                    }
                    $sqlScript .= "INSERT INTO `$table` VALUES (" . implode(',', $escapedValues) . ");\n";
                }
                $sqlScript .= "\n";
            }
        }

        $sqlScript .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Simpan file backup
        write_file($filePath, $sqlScript);

        // Catat log backup ke tabel log_database
        $this->db->table('log_database')->insert([
            'type'        => 'backup',
            'filename'    => $filename,
            'notes'       => 'Backup database via web',
            'created_by'  => session('username') ?? 'admin',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        // Kirim file ke browser untuk diunduh
        return $this->response->download($filePath, null)->setFileName($filename);
    }


    // =====================================
    // === IMPORT DATABASE (UPLOAD .SQL) ===
    // =====================================
    public function import()
    {
        $file = $this->request->getFile('importFile');
        // if (!$file->isValid() || strtolower($file->getExtension()) !== 'sql') {
        //     return $this->response->setStatusCode(400)->setJSON(['error' => 'File tidak valid. Harus file .sql']);
        // }

        $tmpPath = WRITEPATH . 'uploads/';
        if (!is_dir($tmpPath)) mkdir($tmpPath, 0777, true);

        $fileName = 'import_' . date('Ymd_His') . '.sql';
        $file->move($tmpPath, $fileName);
        $filePath = $tmpPath . $fileName;

        $sql = file_get_contents($filePath);
        $queries = preg_split('/;\s*[\r\n]+/', $sql);

        $this->db->transStart();
        foreach ($queries as $query) {
            $query = trim($query);
            if ($query !== '' && !str_starts_with($query, '--')) {
                try {
                    $this->db->query($query);
                } catch (\Throwable $e) {
                    log_message('error', 'SQL import error: ' . $e->getMessage());
                }
            }
        }
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Import gagal. Ada query tidak valid.']);
        }

        // Simpan log
        $this->db->table('log_database')->insert([
            'type' => 'import',
            'filename' => $fileName,
            'notes' => 'Import SQL manual via web',
            'created_by' => session('username') ?? 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        @unlink($filePath);

        return $this->response->setJSON(['success' => true]);
    }

    // =====================================
    // === GET LOG DATABASE HISTORY (JSON) ==
    // =====================================
    public function logs()
    {
        $logs = $this->db->table('log_database')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($logs);
    }
}
