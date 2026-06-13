<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PenilaianSkpModel;
use App\Models\RhkModel;
use App\Models\RealisasiModel;
use App\Models\NotifikasiModel;

class Penilaian extends BaseController
{
    public function index()
    {
        $skpModel = new SkpModel();
        $userId = session()->get('id');
        $userRole = session()->get('role');
        
        // Ambil SKP yang sudah disetujui dan siap dinilai (bawahan)
        $query = $skpModel->select('skp_master.*, users.nama_lengkap as user_name, users.unit_kerja, users.jabatan, periode.tahun, periode.nama_periode')
                          ->join('users', 'users.id = skp_master.user_id')
                          ->join('periode', 'periode.id = skp_master.periode_id')
                          ->where('skp_master.status', 'disetujui');
        
        if ($userRole === 'rektor') {
            // Rektor bisa nilai semua
        } else {
            $query->where('users.atasan_id', $userId);
        }
        
        $skpList = $query->orderBy('skp_master.tanggal_approval', 'DESC')->findAll();
        
        $data = [
            'title' => 'Penilaian SKP',
            'skpList' => $skpList
        ];
        
        return view('penilaian/index', $data);
    }

    public function create($skpId)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->getSkpWithDetails($skpId);
        
        if (!$skp) {
            return redirect()->to('/penilaian')->with('error', 'SKP tidak ditemukan');
        }
        
        // Check if already assessed
        $penilaianModel = new PenilaianSkpModel();
        $existing = $penilaianModel->where('skp_id', $skpId)->first();
        
        // Get RHK and realisasi data for assessment
        $rhkModel = new RhkModel();
        $realisasiModel = new RealisasiModel();
        
        $rhkList = $rhkModel->getBySkp($skpId);
        $progress = $realisasiModel->getProgressBySkp($skpId);
        
        $data = [
            'title' => 'Penilaian SKP',
            'skp' => $skp,
            'rhkList' => $rhkList,
            'progress' => $progress,
            'existing' => $existing
        ];
        
        return view('penilaian/create', $data);
    }

    public function store()
    {
        $skpId = $this->request->getPost('skp_id');
        $nilaiKuantitas = $this->request->getPost('nilai_kuantitas');
        $nilaiKualitas = $this->request->getPost('nilai_kualitas');
        $nilaiWaktu = $this->request->getPost('nilai_waktu');
        $catatan = $this->request->getPost('catatan_penilai');
        
        // Hitung nilai total (bobot: kuantitas 50%, kualitas 30%, waktu 20%)
        $nilaiTotal = ($nilaiKuantitas * 0.5) + ($nilaiKualitas * 0.3) + ($nilaiWaktu * 0.2);
        
        // Tentukan predikat
        if ($nilaiTotal >= 91) {
            $predikat = 'ISTIMEWA';
        } elseif ($nilaiTotal >= 76) {
            $predikat = 'BAIK';
        } elseif ($nilaiTotal >= 61) {
            $predikat = 'CUKUP';
        } elseif ($nilaiTotal >= 51) {
            $predikat = 'KURANG';
        } else {
            $predikat = 'BURUK';
        }
        
        $penilaianModel = new PenilaianSkpModel();
        $existing = $penilaianModel->where('skp_id', $skpId)->first();
        
        $data = [
            'skp_id' => $skpId,
            'nilai_kuantitas' => $nilaiKuantitas,
            'nilai_kualitas' => $nilaiKualitas,
            'nilai_waktu' => $nilaiWaktu,
            'nilai_total' => $nilaiTotal,
            'predikat' => $predikat,
            'catatan_penilai' => $catatan,
            'tanggal_penilaian' => date('Y-m-d'),
            'penilai_id' => session()->get('id'),
            'status_penilaian' => 'final'
        ];
        
        if ($existing) {
            $penilaianModel->update($existing['id'], $data);
        } else {
            $penilaianModel->insert($data);
        }
        
        // Update SKP status and nilai
        $skpModel = new SkpModel();
        $skpModel->update($skpId, [
            'status' => 'selesai',
            'nilai_akhir' => $nilaiTotal,
            'predikat' => $predikat
        ]);
        
        // Notifikasi ke pegawai yang dinilai
        $skp = $skpModel->find($skpId);
        $notifikasiModel = new NotifikasiModel();
        $notifikasiModel->addNotifikasi(
            $skp['user_id'],
            'Penilaian SKP Selesai',
            'SKP Anda telah dinilai dengan nilai ' . $nilaiTotal . ' (' . $predikat . ')',
            '/skp/detail/' . $skpId
        );
        
        return redirect()->to('/penilaian')->with('success', 'Penilaian berhasil disimpan');
    }
}