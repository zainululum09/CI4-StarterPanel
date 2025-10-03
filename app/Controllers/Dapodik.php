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
            'title'         => 'Dapodik',
            'datasiswa'     => $tr['data'],
            'total'         => $tr['total']
        ]);
        return view('pages/commons/dapodik', $data);
    }
}
		