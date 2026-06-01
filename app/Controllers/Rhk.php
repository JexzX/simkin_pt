<?php

namespace App\Controllers;

use App\Models\RhkModel;
use App\Models\RhkIndikatorModel;
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
        
        $data = [
            'title' => 'Tambah RHK',
            'skp_id' => $skpId,
            'skp' => $skp
        ];
        
        // Jika user bukan rektor, tampilkan pilihan intervensi
        $role = session()->get('role');
        if (!in_array($role, ['rektor', 'super_admin', 'admin_perencana'])) {
            $masterIkskModel = new MasterIkskModel();
            $intervensiList = $masterIkskModel->getByPicUnit(session()->get('unit_kerja'));
            $data['intervensiList'] = $intervensiList;
        }
        
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
        $rhkId = $rhkModel->getInsertID();
        
        // Tambah indikator jika ada
        $indikatorList = $this->request->getPost('indikator');
        $targetList = $this->request->getPost('target_indikator');
        
        if ($indikatorList && is_array($indikatorList)) {
            $indikatorModel = new RhkIndikatorModel();
            foreach ($indikatorList as $key => $indikator) {
                if (!empty($indikator)) {
                    $indikatorModel->insert([
                        'rhk_id' => $rhkId,
                        'indikator' => $indikator,
                        'target' => $targetList[$key] ?? '',
                        'perspektif' => 'KUANTITAS'
                    ]);
                }
            }
        }
        
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
        
        $indikatorModel = new RhkIndikatorModel();
        $indikator = $indikatorModel->getByRhk($id);
        
        $data = [
            'title' => 'Edit RHK',
            'rhk' => $rhk,
            'indikator' => $indikator,
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

    public function storeIndikator()
    {
        $rhkId = $this->request->getPost('rhk_id');
        $indikator = $this->request->getPost('indikator');
        $target = $this->request->getPost('target');
        
        if (!$rhkId || !$indikator) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }
        
        $indikatorModel = new RhkIndikatorModel();
        $indikatorModel->insert([
            'rhk_id' => $rhkId,
            'indikator' => $indikator,
            'target' => $target
        ]);
        
        return $this->response->setJSON(['success' => true, 'id' => $indikatorModel->getInsertID()]);
    }

    public function deleteIndikator($id)
    {
        $indikatorModel = new RhkIndikatorModel();
        $indikator = $indikatorModel->find($id);
        
        if (!$indikator) {
            return $this->response->setJSON(['success' => false]);
        }
        
        $indikatorModel->delete($id);
        
        return $this->response->setJSON(['success' => true]);
    }

    public function getIntervensi()
    {
        $unitKerja = session()->get('unit_kerja');
        $masterIkskModel = new MasterIkskModel();
        
        $intervensi = $masterIkskModel->where('pic_unit', $unitKerja)
                                      ->where('tahun', date('Y'))
                                      ->findAll();
        
        return $this->response->setJSON($intervensi);
    }
}