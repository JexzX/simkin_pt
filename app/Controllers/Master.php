<?php

namespace App\Controllers;

use App\Models\MasterSpModel;
use App\Models\MasterSkModel;
use App\Models\MasterIkskModel;

class Master extends BaseController
{
    // ======================================================
    // SP (Sasaran Program)
    // ======================================================
    public function sp()
    {
        $masterSpModel = new MasterSpModel();
        $data = [
            'title' => 'Master Sasaran Program (SP)',
            'spList' => $masterSpModel->getWithSk()
        ];
        return view('master/sp', $data);
    }

    public function storeSp()
    {
        $rules = [
            'kode_sp' => 'required|is_unique[master_sp.kode_sp]',
            'nama_sp' => 'required',
            'tahun' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $masterSpModel = new MasterSpModel();
        $masterSpModel->insert([
            'kode_sp' => $this->request->getPost('kode_sp'),
            'nama_sp' => $this->request->getPost('nama_sp'),
            'indikator_iksp' => $this->request->getPost('indikator_iksp'),
            'tahun' => $this->request->getPost('tahun'),
            'created_by' => session()->get('id')
        ]);
        
        return redirect()->to('/master/sp')->with('success', 'SP berhasil ditambahkan');
    }

    public function updateSp($id)
    {
        $masterSpModel = new MasterSpModel();
        $masterSpModel->update($id, [
            'nama_sp' => $this->request->getPost('nama_sp'),
            'indikator_iksp' => $this->request->getPost('indikator_iksp'),
            'tahun' => $this->request->getPost('tahun')
        ]);
        
        return redirect()->to('/master/sp')->with('success', 'SP berhasil diupdate');
    }

    public function deleteSp($id)
    {
        $masterSpModel = new MasterSpModel();
        $masterSpModel->delete($id);
        return redirect()->to('/master/sp')->with('success', 'SP berhasil dihapus');
    }

    // ======================================================
    // SK (Sasaran Kegiatan)
    // ======================================================
    public function sk()
    {
        $masterSkModel = new MasterSkModel();
        $masterSpModel = new MasterSpModel();
        
        $data = [
            'title' => 'Master Sasaran Kegiatan (SK)',
            'skList' => $masterSkModel->getWithIksk(),
            'spList' => $masterSpModel->findAll()
        ];
        return view('master/sk', $data);
    }

    public function storeSk()
    {
        $rules = [
            'kode_sk' => 'required|is_unique[master_sk.kode_sk]',
            'nama_sk' => 'required',
            'sp_id' => 'required|numeric',
            'tahun' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $masterSkModel = new MasterSkModel();
        $masterSkModel->insert([
            'kode_sk' => $this->request->getPost('kode_sk'),
            'nama_sk' => $this->request->getPost('nama_sk'),
            'sp_id' => $this->request->getPost('sp_id'),
            'tahun' => $this->request->getPost('tahun'),
            'created_by' => session()->get('id')
        ]);
        
        return redirect()->to('/master/sk')->with('success', 'SK berhasil ditambahkan');
    }

    public function updateSk($id)
    {
        $masterSkModel = new MasterSkModel();
        $masterSkModel->update($id, [
            'nama_sk' => $this->request->getPost('nama_sk'),
            'sp_id' => $this->request->getPost('sp_id'),
            'tahun' => $this->request->getPost('tahun')
        ]);
        
        return redirect()->to('/master/sk')->with('success', 'SK berhasil diupdate');
    }

    public function deleteSk($id)
    {
        $masterSkModel = new MasterSkModel();
        $masterSkModel->delete($id);
        return redirect()->to('/master/sk')->with('success', 'SK berhasil dihapus');
    }

    // ======================================================
    // IKSK (Indikator SK)
    // ======================================================
    public function iksk()
    {
        $masterIkskModel = new MasterIkskModel();
        $masterSkModel = new MasterSkModel();
        
        $data = [
            'title' => 'Master Indikator SK (IKSK)',
            'ikskList' => $masterIkskModel->getWithSk(),
            'skList' => $masterSkModel->findAll()
        ];
        return view('master/iksk', $data);
    }

    public function storeIksk()
    {
        $rules = [
            'kode_iksk' => 'required|is_unique[master_iksk.kode_iksk]',
            'nama_iksk' => 'required',
            'sk_id' => 'required|numeric',
            'tahun' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $masterIkskModel = new MasterIkskModel();
        $masterIkskModel->insert([
            'kode_iksk' => $this->request->getPost('kode_iksk'),
            'nama_iksk' => $this->request->getPost('nama_iksk'),
            'sk_id' => $this->request->getPost('sk_id'),
            'target_awal' => $this->request->getPost('target_awal'),
            'pic_unit' => $this->request->getPost('pic_unit'),
            'tahun' => $this->request->getPost('tahun'),
            'created_by' => session()->get('id')
        ]);
        
        return redirect()->to('/master/iksk')->with('success', 'IKSK berhasil ditambahkan');
    }

    public function updateIksk($id)
    {
        $masterIkskModel = new MasterIkskModel();
        $masterIkskModel->update($id, [
            'nama_iksk' => $this->request->getPost('nama_iksk'),
            'sk_id' => $this->request->getPost('sk_id'),
            'target_awal' => $this->request->getPost('target_awal'),
            'pic_unit' => $this->request->getPost('pic_unit'),
            'tahun' => $this->request->getPost('tahun')
        ]);
        
        return redirect()->to('/master/iksk')->with('success', 'IKSK berhasil diupdate');
    }

    public function deleteIksk($id)
    {
        $masterIkskModel = new MasterIkskModel();
        $masterIkskModel->delete($id);
        return redirect()->to('/master/iksk')->with('success', 'IKSK berhasil dihapus');
    }
}