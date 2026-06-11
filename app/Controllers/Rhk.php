<?php

namespace App\Controllers;

use App\Models\RhkModel;
use App\Models\SkpModel;
use App\Models\UserModel;

class Rhk extends BaseController
{
    public function create($skpId)
    {
        $skpModel = new SkpModel();
        $rhkModel = new RhkModel();
        $userModel = new UserModel();
        
        $skp = $skpModel->find($skpId);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        if ($skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'SKP sudah diajukan, tidak dapat menambah RHK');
        }
        
        // Ambil intervensi dari atasan (RHK atasan yang sudah disetujui)
        $intervensiList = [];
        $currentUserId = session()->get('id');
        $currentUser = $userModel->find($currentUserId);
        $atasanId = $currentUser['atasan_id'] ?? null;
        
        if ($atasanId) {
            // Cari SKP atasan yang sudah disetujui
            $skpAtasan = $skpModel->where('user_id', $atasanId)
                                  ->where('status', 'disetujui')
                                  ->first();
            
            if ($skpAtasan) {
                // Ambil RHK dari SKP atasan
                $intervensiList = $rhkModel->where('skp_id', $skpAtasan['id'])->findAll();
            }
        }
        
        $data = [
            'title' => 'Tambah RHK',
            'skp_id' => $skpId,
            'intervensiList' => $intervensiList
        ];
        
        return view('rhk/create', $data);
    }
    
    public function store()
    {
        $rhkModel = new RhkModel();
        
        $rules = [
            'skp_id' => 'required|numeric',
            'nama_rhk' => 'required|min_length[3]',
            'jenis_rhk' => 'required|in_list[kuantitatif,kualitatif]',
            'klasifikasi' => 'required|in_list[utama,tambahan]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'skp_id' => $this->request->getPost('skp_id'),
            'nama_rhk' => $this->request->getPost('nama_rhk'),
            'jenis_rhk' => $this->request->getPost('jenis_rhk'),
            'klasifikasi' => $this->request->getPost('klasifikasi'),
            'target_kuantitas' => $this->request->getPost('target_kuantitas') ?: null,
            'target_satuan' => $this->request->getPost('target_satuan'),
            'target_kualitas' => $this->request->getPost('target_kualitas'),
            'target_waktu' => $this->request->getPost('target_waktu'),
            'bobot' => $this->request->getPost('bobot') ?: 0
        ];
        
        // ========== INI PENTING: Simpan intervensi dari atasan ==========
        $intervensiId = $this->request->getPost('intervensi_dari_id');
        if (!empty($intervensiId)) {
            $data['intervensi_dari_type'] = $this->request->getPost('intervensi_dari_type');
            $data['intervensi_dari_id'] = $intervensiId;
        }
        // =================================================================
        
        $rhkModel->insert($data);
        
        return redirect()->to('/skp/detail/' . $this->request->getPost('skp_id'))
                        ->with('success', 'RHK berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($id);
        
        if (!$rhk) {
            return redirect()->back()->with('error', 'RHK tidak ditemukan');
        }
        
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        if ($skp['user_id'] != session()->get('id') || $skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat mengedit RHK');
        }
        
        $data = [
            'title' => 'Edit RHK',
            'rhk' => $rhk,
            'skp_id' => $rhk['skp_id']
        ];
        
        return view('rhk/edit', $data);
    }
    
    public function update($id)
    {
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($id);
        
        if (!$rhk) {
            return redirect()->back()->with('error', 'RHK tidak ditemukan');
        }
        
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        if ($skp['user_id'] != session()->get('id') || $skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat mengedit RHK');
        }
        
        $data = [
            'nama_rhk' => $this->request->getPost('nama_rhk'),
            'jenis_rhk' => $this->request->getPost('jenis_rhk'),
            'klasifikasi' => $this->request->getPost('klasifikasi'),
            'target_kuantitas' => $this->request->getPost('target_kuantitas') ?: null,
            'target_satuan' => $this->request->getPost('target_satuan'),
            'target_kualitas' => $this->request->getPost('target_kualitas'),
            'target_waktu' => $this->request->getPost('target_waktu'),
            'bobot' => $this->request->getPost('bobot') ?: 0
        ];
        
        $rhkModel->update($id, $data);
        
        return redirect()->to('/skp/detail/' . $rhk['skp_id'])
                        ->with('success', 'RHK berhasil diupdate');
    }
    
    public function delete($id)
    {
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($id);
        
        if (!$rhk) {
            return redirect()->back()->with('error', 'RHK tidak ditemukan');
        }
        
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        if ($skp['user_id'] != session()->get('id') || $skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus RHK');
        }
        
        $skpId = $rhk['skp_id'];
        $rhkModel->delete($id);
        
        return redirect()->to('/skp/detail/' . $skpId)
                        ->with('success', 'RHK berhasil dihapus');
    }
}