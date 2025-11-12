<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Spmb extends BaseController
{
    public function index()
    {
        $data = array_merge($this->data, [
            'title'         => 'Spmb'
        ]);
        return view('spmb', $data);
    }
}
		