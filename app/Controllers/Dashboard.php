<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $data = [
            'nama' => session()->get('nama_lengkap'),
            'role' => session()->get('role'),
            'unit_kerja' => session()->get('unit_kerja')
        ];
        
        return view('dashboard/index', $data);
    }
}