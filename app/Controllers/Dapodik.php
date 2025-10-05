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
        $tr = $this->dapodikModel->getAllPd();
        $data = array_merge($this->data, [
            'title'         => 'Daftar Peserta Didik',
            'datasiswa'     => $tr['data'],
            'total'         => $tr['total']
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
		