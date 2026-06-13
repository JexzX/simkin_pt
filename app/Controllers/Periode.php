<?php

namespace App\Controllers;

use App\Models\PeriodeModel;

class Periode extends BaseController
{
    public function index()
    {
        $model = new PeriodeModel();
        $data['periodeList'] = $model->orderBy('tahun', 'DESC')->orderBy('created_at', 'DESC')->findAll();
        return view('periode/index', $data);
    }

    public function create()
    {
        return view('periode/create');
    }

    public function store()
    {
        $model = new PeriodeModel();

        $rules = [
            'tahun'           => 'required|integer',
            'nama_periode'    => 'required',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Periksa kembali input Anda.');
        }

        $data = [
            'tahun'          => $this->request->getPost('tahun'),
            'nama_periode'   => $this->request->getPost('nama_periode'),
            'tanggal_mulai'  => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'=> $this->request->getPost('tanggal_selesai'),
            'batas_akhir_pengajuan_skp' => $this->request->getPost('batas_akhir_pengajuan_skp'),
            'batas_akhir_realisasi'     => $this->request->getPost('batas_akhir_realisasi'),
            'batas_akhir_penilaian'     => $this->request->getPost('batas_akhir_penilaian'),
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($data['is_active']) {
            $model->builder()->update(['is_active' => 0]);
        }

        $model->save($data);

        return redirect()->to('/periode')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new PeriodeModel();
        $data['periode'] = $model->find($id);
        if (!$data['periode']) {
            return redirect()->to('/periode')->with('error', 'Periode tidak ditemukan.');
        }
        return view('periode/edit', $data);
    }

    public function update($id)
    {
        $model = new PeriodeModel();
        $periode = $model->find($id);
        if (!$periode) {
            return redirect()->to('/periode')->with('error', 'Periode tidak ditemukan.');
        }

        $data = [
            'tahun'          => $this->request->getPost('tahun'),
            'nama_periode'   => $this->request->getPost('nama_periode'),
            'tanggal_mulai'  => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'=> $this->request->getPost('tanggal_selesai'),
            'batas_akhir_pengajuan_skp' => $this->request->getPost('batas_akhir_pengajuan_skp'),
            'batas_akhir_realisasi'     => $this->request->getPost('batas_akhir_realisasi'),
            'batas_akhir_penilaian'     => $this->request->getPost('batas_akhir_penilaian'),
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($data['is_active']) {
            $model->builder()->update(['is_active' => 0]);
        }

        $model->update($id, $data);

        return redirect()->to('/periode')->with('success', 'Periode berhasil diupdate.');
    }

    public function delete($id)
    {
        $model = new PeriodeModel();
        $periode = $model->find($id);
        if (!$periode) {
            return redirect()->to('/periode')->with('error', 'Periode tidak ditemukan.');
        }

        $model->delete($id);
        return redirect()->to('/periode')->with('success', 'Periode berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $model = new PeriodeModel();
        $periode = $model->find($id);
        if (!$periode) {
            return redirect()->to('/periode')->with('error', 'Periode tidak ditemukan.');
        }

        $newStatus = $periode['is_active'] ? 0 : 1;

        if ($newStatus) {
            $model->builder()->update(['is_active' => 0]);
        }

        $model->update($id, ['is_active' => $newStatus]);

        $msg = $newStatus ? 'Periode diaktifkan.' : 'Periode dinonaktifkan.';
        return redirect()->to('/periode')->with('success', $msg);
    }
}
