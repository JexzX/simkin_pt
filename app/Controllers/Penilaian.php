<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PenilaianSkpModel;
use App\Models\RhkModel;
use App\Models\RhkIndikatorModel;
use App\Models\RealisasiModel;
use App\Models\NotifikasiModel;

class Penilaian extends BaseController
{
    public function index()
    {
        $skpModel = new SkpModel();
        $userId = session()->get('id');
        $userRole = session()->get('role');
        
        $query = $skpModel->select('skp_master.*, users.nama_lengkap as user_name, users.unit_kerja, users.jabatan, periode.tahun, periode.nama_periode')
                          ->join('users', 'users.id = skp_master.user_id')
                          ->join('periode', 'periode.id = skp_master.periode_id')
                          ->where('skp_master.status', 'disetujui');
        
        if ($userRole !== 'rektor') {
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
        
        $penilaianModel = new PenilaianSkpModel();
        $existing = $penilaianModel->where('skp_id', $skpId)->first();
        
        $rhkModel = new RhkModel();
        $indikatorModel = new RhkIndikatorModel();
        $realisasiModel = new RealisasiModel();
        
        $rhkList = $rhkModel->getBySkp($skpId);
        $progress = $realisasiModel->getProgressBySkp($skpId);
        
        // Get indikator with realisasi for each RHK
        $rhkIndikators = [];
        foreach ($rhkList as $rhk) {
            $indikators = $indikatorModel->where('rhk_id', $rhk['id'])->findAll();
            foreach ($indikators as &$ind) {
                $realisasi = $realisasiModel->selectSum('realisasi_kuantitas')
                    ->where('rhk_indikator_id', $ind['id'])
                    ->where('status', 'disetujui')
                    ->first();
                $ind['total_realisasi'] = $realisasi['realisasi_kuantitas'] ?? 0;
            }
            $rhkIndikators[$rhk['id']] = $indikators;
        }
        
        // Parse existing rincian_nilai if any
        $rincianNilai = [];
        if ($existing && !empty($existing['rincian_nilai'])) {
            $rincianNilai = json_decode($existing['rincian_nilai'], true) ?: [];
        }
        
        $data = [
            'title' => 'Penilaian SKP',
            'skp' => $skp,
            'rhkList' => $rhkList,
            'progress' => $progress,
            'rhkIndikators' => $rhkIndikators,
            'existing' => $existing,
            'rincianNilai' => $rincianNilai
        ];
        
        return view('penilaian/create', $data);
    }

    public function store()
    {
        $skpId = $this->request->getPost('skp_id');
        $nilaiIndikator = $this->request->getPost('nilai_indikator') ?: [];
        $catatan = $this->request->getPost('catatan_penilai');
        
        // Compute average of all indikator scores
        $scores = array_values($nilaiIndikator);
        $totalScore = !empty($scores) ? array_sum($scores) / count($scores) : 0;
        $nilaiTotal = round($totalScore, 2);
        
        // Determine predikat
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
            'nilai_kuantitas' => null,
            'nilai_kualitas' => null,
            'nilai_waktu' => null,
            'nilai_total' => $nilaiTotal,
            'predikat' => $predikat,
            'rincian_nilai' => json_encode($nilaiIndikator),
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
        
        $skpModel = new SkpModel();
        $skpModel->update($skpId, [
            'status' => 'selesai',
            'nilai_akhir' => $nilaiTotal,
            'predikat' => $predikat
        ]);
        
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