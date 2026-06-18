<?php

namespace App\Controllers;

use App\Models\RhkModel;
use App\Models\SkpModel;
use App\Models\UserModel;
use App\Models\RhkIndikatorModel;

class Rhk extends BaseController
{
    public function create($skpId)
    {
        $skpModel = new SkpModel();
        $rhkModel = new RhkModel();
        $userModel = new UserModel();
        $indikatorModel = new RhkIndikatorModel();
        
        $skp = $skpModel->find($skpId);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        if ($skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'SKP sudah diajukan, tidak dapat menambah RHK');
        }
        
        $currentUserId = session()->get('id');
        $currentUser = $userModel->find($currentUserId);
        $atasanId = $currentUser['atasan_id'] ?? null;
        $isRektor = ($currentUser['role'] === 'rektor');
        
        // Ambil RHK atasan yang sudah disetujui untuk intervensi
        $intervensiList = [];
        
        if ($atasanId) {
            $skpAtasan = $skpModel->where('user_id', $atasanId)
                                  ->where('status', 'disetujui')
                                  ->first();
            
            if ($skpAtasan) {
                $intervensiList = $rhkModel->where('skp_id', $skpAtasan['id'])->findAll();
                foreach ($intervensiList as &$rhkAtasan) {
                    $rhkAtasan['indikator'] = $indikatorModel->getByRhk($rhkAtasan['id']);
                }
            }
        }
        
        $data = [
            'title' => 'Tambah RHK',
            'skp_id' => $skpId,
            'intervensiList' => $intervensiList,
            'skp' => $skp,
            'isRektor' => $isRektor
        ];
        
        return view('rhk/create', $data);
    }
    
    public function store()
    {
        $rhkModel = new RhkModel();
        
        $data = [
            'skp_id' => $this->request->getPost('skp_id'),
            'nama_rhk' => $this->request->getPost('nama_rhk'),
            'klasifikasi' => $this->request->getPost('klasifikasi'),
            'intervensi_indikator_id' => $this->request->getPost('intervensi_indikator_id') ?: null,
            'intervensi_dari_manual' => $this->request->getPost('intervensi_dari_manual') ?: null
        ];
        
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
        
        $userModel = new UserModel();
        $currentUser = $userModel->find(session()->get('id'));
        $atasanId = $currentUser['atasan_id'] ?? null;
        $isRektor = ($currentUser['role'] === 'rektor');
        
        $indikatorModel = new RhkIndikatorModel();
        $intervensiList = [];
        $selectedIndikatorId = $rhk['intervensi_indikator_id'];
        $selectedRhkId = null;
        
        if ($selectedIndikatorId) {
            $ind = $indikatorModel->find($selectedIndikatorId);
            if ($ind) {
                $selectedRhkId = $ind['rhk_id'];
            }
        }
        
        if ($atasanId) {
            $atasanSkp = $skpModel->where('user_id', $atasanId)
                ->where('status', 'disetujui')
                ->first();
            if ($atasanSkp) {
                $intervensiList = $rhkModel->where('skp_id', $atasanSkp['id'])->findAll();
                foreach ($intervensiList as &$rhkAtasan) {
                    $rhkAtasan['indikator'] = $indikatorModel->getByRhk($rhkAtasan['id']);
                }
            }
        }
        
        $data = [
            'title' => 'Edit RHK',
            'rhk' => $rhk,
            'skp_id' => $rhk['skp_id'],
            'skp' => $skp,
            'isRektor' => $isRektor,
            'intervensiList' => $intervensiList,
            'selectedIndikatorId' => $selectedIndikatorId,
            'selectedRhkId' => $selectedRhkId
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
            'klasifikasi' => $this->request->getPost('klasifikasi'),
            'intervensi_indikator_id' => $this->request->getPost('intervensi_indikator_id') ?: null,
            'intervensi_dari_manual' => $this->request->getPost('intervensi_dari_manual') ?: null
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

    // Indikator CRUD
    public function indikatorCreate($rhkId)
    {
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($rhkId);
        
        if (!$rhk) {
            return redirect()->back()->with('error', 'RHK tidak ditemukan');
        }
        
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        if ($skp['user_id'] != session()->get('id') || $skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat menambah indikator');
        }
        
        $userModel = new UserModel();
        $user = $userModel->find($skp['user_id']);
        $isRektor = ($user && $user['role'] === 'rektor');
        
        $skp = $skpModel->getSkpWithDetails($rhk['skp_id']);
        
        $data = [
            'title' => 'Tambah Indikator',
            'rhk' => $rhk,
            'skp' => $skp,
            'isRektor' => $isRektor
        ];
        
        return view('rhk/indikator_create', $data);
    }
    
    public function indikatorStore()
    {
        $indikatorModel = new RhkIndikatorModel();
        
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($this->request->getPost('rhk_id'));
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        $userModel = new UserModel();
        $user = $userModel->find($skp['user_id']);
        $isRektor = ($user && $user['role'] === 'rektor');
        
        if ($isRektor) {
            $data = [
                'rhk_id' => $this->request->getPost('rhk_id'),
                'indikator' => $this->request->getPost('indikator_manual'),
                'target' => $this->request->getPost('indikator_manual'),
                'aspek' => 'Manual'
            ];
        } else {
            $data = [
                'rhk_id' => $this->request->getPost('rhk_id'),
                'indikator' => $this->request->getPost('indikator'),
                'target' => $this->request->getPost('target'),
                'aspek' => $this->request->getPost('aspek')
            ];
        }
        
        $indikatorModel->insert($data);
        
        return redirect()->to('/skp/detail/' . $rhk['skp_id'])
                        ->with('success', 'Indikator berhasil ditambahkan');
    }
    
    public function indikatorEdit($id)
    {
        $indikatorModel = new RhkIndikatorModel();
        $indikator = $indikatorModel->find($id);
        
        if (!$indikator) {
            return redirect()->back()->with('error', 'Indikator tidak ditemukan');
        }
        
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($indikator['rhk_id']);
        
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        if ($skp['user_id'] != session()->get('id') || $skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat mengedit indikator');
        }
        
        $userModel = new UserModel();
        $user = $userModel->find($skp['user_id']);
        $isRektor = ($user && $user['role'] === 'rektor');
        
        $skpWithDetails = $skpModel->getSkpWithDetails($rhk['skp_id']);
        
        $data = [
            'title' => 'Edit Indikator',
            'indikator' => $indikator,
            'rhk' => $rhk,
            'skp' => $skpWithDetails,
            'isRektor' => $isRektor
        ];
        
        return view('rhk/indikator_edit', $data);
    }
    
    public function indikatorUpdate($id)
    {
        $indikatorModel = new RhkIndikatorModel();
        $indikator = $indikatorModel->find($id);
        
        if (!$indikator) {
            return redirect()->back()->with('error', 'Indikator tidak ditemukan');
        }
        
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($indikator['rhk_id']);
        
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        if ($skp['user_id'] != session()->get('id') || $skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat mengedit indikator');
        }
        
        $userModel = new UserModel();
        $user = $userModel->find($skp['user_id']);
        $isRektor = ($user && $user['role'] === 'rektor');
        
        if ($isRektor) {
            $data = [
                'indikator' => $this->request->getPost('indikator_manual'),
                'target' => $this->request->getPost('indikator_manual'),
                'aspek' => 'Manual'
            ];
        } else {
            $data = [
                'aspek' => $this->request->getPost('aspek'),
                'indikator' => $this->request->getPost('indikator'),
                'target' => $this->request->getPost('target'),
            ];
        }
        
        $indikatorModel->update($id, $data);
        
        return redirect()->to('/skp/detail/' . $rhk['skp_id'])
                        ->with('success', 'Indikator berhasil diupdate');
    }

    public function indikatorDelete($id)
    {
        $indikatorModel = new RhkIndikatorModel();
        $indikator = $indikatorModel->find($id);
        
        if (!$indikator) {
            return redirect()->back()->with('error', 'Indikator tidak ditemukan');
        }
        
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($indikator['rhk_id']);
        
        $indikatorModel->delete($id);
        
        return redirect()->to('/skp/detail/' . $rhk['skp_id'])
                        ->with('success', 'Indikator berhasil dihapus');
    }
}
