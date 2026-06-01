<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = session()->get('role');
        $nama = session()->get('nama_lengkap');
        
        $data = [
            'title' => 'Dashboard',
            'nama'  => $nama,
            'role'  => $role,
        ];
        
        return view('dashboard/index', $data);
    }
}