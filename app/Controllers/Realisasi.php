<?php

namespace App\Controllers;

class Realisasi extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Realisasi Kinerja',
        ];
        return view('realisasi/index', $data);
    }
}