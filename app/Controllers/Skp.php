<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PeriodeModel;

class Skp extends BaseController
{
    public function index()
    {
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        
        $periodeAktif = $periodeModel->getActivePeriode();
        $skpList = $skpModel->getSkpByUser(session()->get('id'), $periodeAktif['id'] ?? null);
        
        $data = [
            'title' => 'Daftar SKP',
            'skpList' => $skpList,
        ];
        
        return view('skp/index', $data);
    }
    
    public function create()
    {
        return view('skp/create');
    }
    
    public function store()
    {
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        
        $periodeAktif = $periodeModel->getActivePeriode();
        
        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Belum ada periode aktif. Hubungi admin.');
        }
        
        $data = [
            'user_id' => session()->get('id'),
            'periode_id' => $periodeAktif['id'],
            'status' => 'draft'
        ];
        
        $skpModel->insert($data);
        
        return redirect()->to('/skp')->with('success', 'SKP berhasil dibuat');
    }
    
    public function detail($id)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->find($id);
        
        if (!$skp) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        $data = [
            'title' => 'Detail SKP',
            'skp' => $skp,
        ];
        
        return view('skp/detail', $data);
    }
}