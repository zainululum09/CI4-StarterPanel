<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DapodikModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Files\File;

class Nilai_siswa extends BaseController
{
    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
    }

    public function index()
    {
        $rombel = $this->dapodikModel->getRombel();
        $data = array_merge($this->data, [
            'title'         => 'Nilai Siswa',
            'rombel'        => $rombel
        ]);
        return view('pages/commons/nilai_siswa', $data);
    }
}
		