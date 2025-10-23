<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LaporanController extends BaseController
{
    public function index()
    {
        $data['title'] = 'Laporan';
        $data['content'] = 'pages/laporan/index';
        return view('layout/main', $data);
    }

    public function fasilitasTableReport()
    {
        $db = db_connect();
        $request = service('request');

        // // Filter input dari datatables atau form
        // $kondisi = $request->getGet('kondisi');
        // $jenis_id = $request->getGet('jenis_fasilitas_id');
        $kab_kota = $request->getGet('kab_kota');
        $kecamatan = $request->getGet('kecamatan');
        $kelurahan = $request->getGet('kelurahan');

        $builder = $db->table('fasilitas');
        $builder->select('kab_kota, kecamatan, kelurahan, nama_ruas, COUNT(*) as total');

        // Terapkan filter jika ada
        // if (!empty($kondisi)) {
        //     $builder->where('kondisi', $kondisi);
        // }

        // if (!empty($jenis_id)) {
        //     $builder->where('jenis_fasilitas_id', $jenis_id);
        // }

        if (!empty($kab_kota)) {
            $builder->where('kab_kota', $kab_kota);
        }

        if (!empty($kecamatan)) {
            $builder->where('kecamatan', $kecamatan);
        }

        if (!empty($kelurahan)) {
            $builder->where('kelurahan', $kelurahan);
        }

        // Group by hierarki
        $builder->groupBy(['kab_kota', 'kecamatan', 'kelurahan', 'nama_ruas']);
        $builder->orderBy('kab_kota, kecamatan, kelurahan, nama_ruas', 'ASC');

        $query = $builder->get()->getResultArray();

        // Format untuk DataTables
        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'kab_kota'   => $row['kab_kota'],
                'kecamatan'  => $row['kecamatan'],
                'kelurahan'  => $row['kelurahan'],
                'nama_ruas'  => $row['nama_ruas'],
                'total'      => (int)$row['total']
            ];
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }

    public function fasilitasByRuas($ruas)
    {
        $db = db_connect();
        $req = service('request');

        $kondisi = $req->getGet('kondisi');
        $jenis = $req->getGet('jenis_fasilitas_id');

        $builder = $db->table('fasilitas');
        $builder->select('id, nama_fasilitas, kondisi, latitude, longitude, foto, catatan, tahun_survey');
        $builder->where('nama_ruas', urldecode($ruas));

        if (!empty($kondisi)) {
            $builder->where('kondisi', $kondisi);
        }
        if (!empty($jenis)) {
            $builder->where('jenis_fasilitas_id', $jenis);
        }

        $result = $builder->get()->getResultArray();

        return $this->response->setJSON(['data' => $result]);
    }

    public function rencanaTableReport()
    {
        $db = db_connect();
        $request = service('request');

        // // Filter input dari datatables atau form
        // $kondisi = $request->getGet('kondisi');
        // $jenis_id = $request->getGet('jenis_fasilitas_id');
        $kab_kota = $request->getGet('kab_kota');
        $kecamatan = $request->getGet('kecamatan');
        $kelurahan = $request->getGet('kelurahan');

        $builder = $db->table('rencana');
        $builder->select('kab_kota, kecamatan, kelurahan, nama_ruas, COUNT(*) as total');

        if (!empty($kab_kota)) {
            $builder->where('kab_kota', $kab_kota);
        }

        if (!empty($kecamatan)) {
            $builder->where('kecamatan', $kecamatan);
        }

        if (!empty($kelurahan)) {
            $builder->where('kelurahan', $kelurahan);
        }
        
        // Group by hierarki
        $builder->groupBy(['kab_kota', 'kecamatan', 'kelurahan', 'nama_ruas']);
        $builder->orderBy('kab_kota, kecamatan, kelurahan, nama_ruas', 'ASC');

        $query = $builder->get()->getResultArray();

        // Format untuk DataTables
        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'kab_kota'   => $row['kab_kota'],
                'kecamatan'  => $row['kecamatan'],
                'kelurahan'  => $row['kelurahan'],
                'nama_ruas'  => $row['nama_ruas'],
                'total'      => (int)$row['total']
            ];
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }

    public function rencanaByRuas($ruas)
    {
        $db = db_connect();
        // $req = service('request');

        // $kondisi = $req->getGet('kondisi');
        // $jenis = $req->getGet('jenis_fasilitas_id');

        $builder = $db->table('rencana');
        $builder->select('id, nama_fasilitas, latitude, longitude, foto, catatan, tahun_survey');
        $builder->where('nama_ruas', urldecode($ruas));

        // if (!empty($kondisi)) {
        //     $builder->where('kondisi', $kondisi);
        // }
        // if (!empty($jenis)) {
        //     $builder->where('jenis_fasilitas_id', $jenis);
        // }

        $result = $builder->get()->getResultArray();

        return $this->response->setJSON(['data' => $result]);
    }
}
