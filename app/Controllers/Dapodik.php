<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dapodik extends BaseController
{
    public function index()
    {
        $data = array_merge($this->data, [
            'title'         => 'Dapodik'
        ]);
        return view('dapodik', $data);
    }
}
		