<?php

namespace App\Controllers;

use App\Models\RhkModel;
use App\Models\SkpModel;
use App\Models\MasterIkskModel;

class Rhk extends BaseController
{
    public function create($skpId)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->find($skpId);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        if ($skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'SKP sudah diajukan, tidak dapat menambah RHK');
        }
        
        // Siapkan data intervensi untuk user non-rektor
        $intervensiList = [];
        $role = session()->get('role');
        if (!in_array($role, ['rektor', 'super_admin', 'admin_perencana'])) {
            $masterIkskModel = new MasterIkskModel();
            $intervensiList = $masterIkskModel->where('pic_unit', session()->get('unit_kerja'))
                                              ->where('tahun', date('Y'))
                                              ->findAll();
        }
        
        $data = [
            'title' => 'Tambah RHK',
            'skp_id' => $skpId,
            'intervensiList' => $intervensiList,
            'role' => $role
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
        
        // Intervensi dari atasan
        $intervensiType = $this->request->getPost('intervensi_type');
        if ($intervensiType == 'iksk') {
            $data['intervensi_dari_type'] = 'master_iksk';
            $data['intervensi_dari_id'] = $this->request->getPost('intervensi_id');
        }
        
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