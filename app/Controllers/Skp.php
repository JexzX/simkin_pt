<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PeriodeModel;
use App\Models\RhkModel;
use App\Models\RhkIndikatorModel;
use App\Models\UserModel;

class Skp extends BaseController
{
    public function index()
    {
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        $userId = session()->get('id');
        
        $periodeAktif = $periodeModel->getActivePeriode();
        $skpList = $skpModel->getSkpByUser($userId, $periodeAktif['id'] ?? null);
        
        $hasSkpAktif = false;
        if ($periodeAktif) {
            $existing = $skpModel->where('user_id', $userId)
                                 ->where('periode_id', $periodeAktif['id'])
                                 ->first();
            $hasSkpAktif = $existing ? true : false;
        }
        
        $data = [
            'title' => 'Daftar SKP',
            'skpList' => $skpList,
            'periodeAktif' => $periodeAktif,
            'hasSkpAktif' => $hasSkpAktif
        ];
        
        return view('skp/index', $data);
    }
    
    public function create()
    {
        $periodeModel = new PeriodeModel();
        $semuaPeriode = $periodeModel->orderBy('tahun', 'DESC')->findAll();
        
        if (empty($semuaPeriode)) {
            return redirect()->back()->with('error', 'Belum ada periode. Hubungi admin.');
        }
        
        $data = [
            'title' => 'Buat SKP Baru',
            'periodeList' => $semuaPeriode
        ];
        
        return view('skp/create', $data);
    }
    
    public function store()
    {
        $skpModel = new SkpModel();
        $userId = session()->get('id');
        $periodeId = $this->request->getPost('periode_id');
        
        $existing = $skpModel->where('user_id', $userId)->where('periode_id', $periodeId)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah memiliki SKP untuk periode ini');
        }
        
        $data = [
            'user_id' => $userId,
            'periode_id' => $periodeId,
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'pendekatan' => $this->request->getPost('pendekatan'),
            'status' => 'draft',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $skpModel->insert($data);
        $skpId = $skpModel->getInsertID();
        
        return redirect()->to('/skp/detail/' . $skpId)->with('success', 'SKP berhasil dibuat. Silakan lengkapi RHK.');
    }
    
    public function detail($id)
    {
        $skpModel = new SkpModel();
        $rhkModel = new RhkModel();
        $indikatorModel = new RhkIndikatorModel();
        $periodeModel = new PeriodeModel();
        
        $skp = $skpModel->getSkpWithDetails($id);
        
        if (!$skp) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        $rhkList = $rhkModel->getBySkp($id);
        
        foreach ($rhkList as &$rhk) {
            $rhk['indikator'] = $indikatorModel->getByRhk($rhk['id']);
            if (!empty($rhk['intervensi_indikator_id'])) {
                $ind = $indikatorModel->find($rhk['intervensi_indikator_id']);
                $rhk['intervensi_dari_terpilih'] = $ind ? [$ind] : [];
                if ($ind) {
                    $rhkAtasan = $rhkModel->find($ind['rhk_id']);
                    $rhk['intervensi_dari_nama'] = $rhkAtasan['nama_rhk'] ?? '-';
                    $rhk['intervensi_dari_data'] = $rhkAtasan;
                    $rhk['intervensi_dari_indikator'] = $indikatorModel->getByRhk($ind['rhk_id']);
                } else {
                    $rhk['intervensi_dari_nama'] = '-';
                    $rhk['intervensi_dari_data'] = null;
                    $rhk['intervensi_dari_indikator'] = [];
                }
            } elseif (!empty($rhk['intervensi_dari_manual'])) {
                $rhk['intervensi_dari_nama'] = $rhk['intervensi_dari_manual'];
                $rhk['intervensi_dari_data'] = null;
                $rhk['intervensi_dari_indikator'] = [];
                $rhk['intervensi_dari_terpilih'] = [];
            } else {
                $rhk['intervensi_dari_nama'] = '-';
                $rhk['intervensi_dari_data'] = null;
                $rhk['intervensi_dari_indikator'] = [];
                $rhk['intervensi_dari_terpilih'] = [];
            }
        }
        
        $userModel = new UserModel();
        $currentUserId = session()->get('id');
        $pembuat = $userModel->find($skp['user_id']);
        $isAtasan = ($pembuat && $pembuat['atasan_id'] == $currentUserId) || session()->get('role') == 'rektor';
        
        $data = [
            'title' => 'Detail SKP',
            'skp' => $skp,
            'rhkList' => $rhkList,
            'isAtasan' => $isAtasan,
            'currentUserId' => $currentUserId
        ];
        
        return view('skp/detail', $data);
    }
    
    public function edit($id)
    {
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        
        $skp = $skpModel->find($id);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        if ($skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'SKP yang sudah diajukan tidak dapat diedit');
        }
        
        $data = [
            'title' => 'Edit SKP',
            'skp' => $skp,
            'periodeList' => $periodeModel->orderBy('tahun', 'DESC')->findAll()
        ];
        
        return view('skp/edit', $data);
    }
    
    public function update($id)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->find($id);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        if ($skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'SKP yang sudah diajukan tidak dapat diedit');
        }
        
        $data = [
            'periode_id' => $this->request->getPost('periode_id'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'pendekatan' => $this->request->getPost('pendekatan'),
        ];
        
        $skpModel->update($id, $data);
        
        return redirect()->to('/skp/detail/' . $id)->with('success', 'SKP berhasil diupdate');
    }
    
    public function delete($id)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->find($id);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        if ($skp['status'] != 'draft') {
            return redirect()->back()->with('error', 'SKP yang sudah diajukan tidak dapat dihapus');
        }
        
        $skpModel->delete($id);
        
        return redirect()->to('/skp')->with('success', 'SKP berhasil dihapus');
    }
    
    public function submit($id)
    {
        $skpModel = new SkpModel();
        $rhkModel = new RhkModel();
        $indikatorModel = new RhkIndikatorModel();
        
        $skp = $skpModel->find($id);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        $rhkList = $rhkModel->getBySkp($id);
        if (empty($rhkList)) {
            return redirect()->back()->with('error', 'Minimal harus ada 1 RHK');
        }
        
        // Wajib: setiap RHK minimal punya 1 indikator
        foreach ($rhkList as $rhk) {
            $indikator = $indikatorModel->getByRhk($rhk['id']);
            if (empty($indikator)) {
                return redirect()->back()->with('error', 'Semua RHK wajib memiliki minimal 1 indikator. RHK "' . esc($rhk['nama_rhk']) . '" belum punya indikator.');
            }
        }
        
        $userRole = session()->get('role');
        if ($userRole === 'rektor') {
            $skpModel->update($id, [
                'status' => 'disetujui',
                'catatan_atasan' => 'Disetujui otomatis (Rektor)',
                'tanggal_pengajuan' => date('Y-m-d H:i:s'),
                'tanggal_approval' => date('Y-m-d H:i:s')
            ]);
            return redirect()->to('/skp/detail/' . $id)->with('success', 'SKP berhasil diajukan dan otomatis disetujui (Rektor).');
        }
        
        $skpModel->update($id, [
            'status' => 'pengajuan',
            'tanggal_pengajuan' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to('/skp/detail/' . $id)->with('success', 'SKP berhasil diajukan. Menunggu persetujuan atasan.');
    }
}