<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DapodikModel;

class Dapodik extends BaseController
{
    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $tr = $this->dapodikModel->getAllPd($page);
        $data = array_merge($this->data, [
            'title'         => 'Daftar Peserta Didik',
            'datasiswa'     => $tr['data'],
            'totalRows' => $tr['totalRows'],
            'currentPage' => $tr['currentPage'],
            'perPage'   => $tr['perPage']
        ]);
        return view('pages/commons/peserta_didik', $data);
    }

    public function detailSiswa($nisn)
    {
        $detail = $this->dapodikModel->getSiswa($nisn);
        $data = array_merge($this->data, [
            'title' => 'Detail Siswa',
            'siswa' => $detail['siswa'],
            'kasus' => $detail['kasus']
        ]);
        // dd($data);
        return view('pages/commons/detail_siswa', $data);
    }
}
		