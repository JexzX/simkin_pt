<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\RhkModel;
use App\Models\RhkIndikatorModel;
use App\Models\RealisasiModel;
use App\Models\PeriodeModel;
use App\Models\NotifikasiModel;

class Realisasi extends BaseController
{
    public function index()
    {
        $skpModel = new SkpModel();
        $rhkModel = new RhkModel();
        $indikatorModel = new RhkIndikatorModel();
        $realisasiModel = new RealisasiModel();
        $periodeModel = new PeriodeModel();
        
        $periodeAktif = $periodeModel->getActivePeriode();
        
        // Cari SKP user yang sudah disetujui atau selesai
        $skp = $skpModel->where('user_id', session()->get('id'))
                        ->where('periode_id', $periodeAktif['id'] ?? 0)
                        ->whereIn('status', ['disetujui', 'selesai'])
                        ->first();
        
        $rhkList = [];
        $realisasiList = [];
        $bulanAktif = $this->request->getGet('bulan') ?: date('n');
        
        if ($skp) {
            $rhkList = $rhkModel->getBySkp($skp['id']);
            foreach ($rhkList as &$rhk) {
                $rhk['indikator'] = $indikatorModel->getByRhk($rhk['id']);
            }
            
            // Ambil realisasi per bulan via join
            $realisasiList = $realisasiModel->select('realisasi.*, rhk.nama_rhk, rhk_indikator.indikator')
                                            ->join('rhk_indikator', 'rhk_indikator.id = realisasi.rhk_indikator_id')
                                            ->join('rhk', 'rhk.id = rhk_indikator.rhk_id')
                                            ->where('rhk.skp_id', $skp['id'])
                                            ->where('realisasi.bulan', $bulanAktif)
                                            ->where('realisasi.tahun', date('Y'))
                                            ->findAll();
        }
        
        $data = [
            'title' => 'Realisasi Kinerja',
            'skp' => $skp,
            'rhkList' => $rhkList,
            'realisasiList' => $realisasiList,
            'bulanAktif' => $bulanAktif,
            'periode' => $periodeAktif
        ];
        
        return view('realisasi/index', $data);
    }
    
    public function create($rhkIndikatorId)
    {
        $indikatorModel = new RhkIndikatorModel();
        $rhkModel = new RhkModel();
        
        $indikator = $indikatorModel->find($rhkIndikatorId);
        
        if (!$indikator) {
            return redirect()->to('/realisasi')->with('error', 'Indikator tidak ditemukan');
        }
        
        $rhk = $rhkModel->find($indikator['rhk_id']);
        
        if (!$rhk) {
            return redirect()->to('/realisasi')->with('error', 'RHK tidak ditemukan');
        }
        
        $bulan = $this->request->getGet('bulan') ?: date('n');
        
        // Cek realisasi existing
        $realisasiModel = new RealisasiModel();
        $existing = $realisasiModel->where('rhk_indikator_id', $rhkIndikatorId)
                                   ->where('bulan', $bulan)
                                   ->where('tahun', date('Y'))
                                   ->first();
        
        $data = [
            'title' => 'Input Realisasi',
            'rhk' => $rhk,
            'indikator' => $indikator,
            'bulan' => $bulan,
            'existing' => $existing
        ];
        
        return view('realisasi/create', $data);
    }
    
    public function store()
    {
        $rules = [
            'rhk_indikator_id' => 'required|numeric',
            'bulan' => 'required|numeric|greater_than[0]|less_than[13]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $realisasiModel = new RealisasiModel();
        
        $rhkIndikatorId = $this->request->getPost('rhk_indikator_id');
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun') ?: date('Y');
        
        $data = [
            'rhk_indikator_id' => $rhkIndikatorId,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'realisasi_kuantitas' => $this->request->getPost('realisasi_kuantitas') ?: null,
            'realisasi_kualitas' => $this->request->getPost('realisasi_kualitas'),
            'realisasi_waktu' => $this->request->getPost('realisasi_waktu'),
            'catatan' => $this->request->getPost('catatan'),
            'status' => 'menunggu_approval',
            'tanggal_realisasi' => date('Y-m-d H:i:s')
        ];
        
        // Handle file upload
        $file = $this->request->getFile('bukti_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/bukti', $newName);
            $data['bukti_file'] = 'uploads/bukti/' . $newName;
        }
        
        // Cek existing
        $existing = $realisasiModel->where('rhk_indikator_id', $rhkIndikatorId)
                                   ->where('bulan', $bulan)
                                   ->where('tahun', $tahun)
                                   ->first();
        
        if ($existing) {
            $realisasiModel->update($existing['id'], $data);
        } else {
            $realisasiModel->insert($data);
        }
        
        return redirect()->to('/realisasi?bulan=' . $bulan)
                        ->with('success', 'Realisasi berhasil disimpan');
    }
    
    public function submit($id)
    {
        $realisasiModel = new RealisasiModel();
        $realisasi = $realisasiModel->find($id);
        
        if (!$realisasi) {
            return redirect()->back()->with('error', 'Realisasi tidak ditemukan');
        }
        
        $realisasiModel->update($id, ['status' => 'menunggu_approval']);
        
        return redirect()->back()->with('success', 'Realisasi diajukan untuk persetujuan');
    }
    
    public function approve($id)
    {
        $realisasiModel = new RealisasiModel();
        $realisasi = $realisasiModel->find($id);
        
        if (!$realisasi) {
            return redirect()->back()->with('error', 'Realisasi tidak ditemukan');
        }
        
        $realisasiModel->update($id, ['status' => 'disetujui']);
        
        // Notifikasi
        $notifikasiModel = new NotifikasiModel();
        $indikatorModel = new RhkIndikatorModel();
        $indikator = $indikatorModel->find($realisasi['rhk_indikator_id']);
        $rhkModel = new RhkModel();
        $rhk = $rhkModel->find($indikator['rhk_id']);
        $skpModel = new SkpModel();
        $skp = $skpModel->find($rhk['skp_id']);
        
        $notifikasiModel->addNotifikasi(
            $skp['user_id'],
            'Realisasi Disetujui',
            'Realisasi bulan ' . $realisasi['bulan'] . ' telah disetujui',
            '/realisasi'
        );
        
        return redirect()->back()->with('success', 'Realisasi berhasil disetujui');
    }
    
    public function reject($id)
    {
        $realisasiModel = new RealisasiModel();
        $realisasi = $realisasiModel->find($id);
        
        if (!$realisasi) {
            return redirect()->back()->with('error', 'Realisasi tidak ditemukan');
        }
        
        $catatan = $this->request->getPost('catatan');
        
        $realisasiModel->update($id, [
            'status' => 'ditolak',
            'catatan' => $catatan
        ]);
        
        return redirect()->back()->with('success', 'Realisasi ditolak');
    }

    public function approvalList()
    {
        $realisasiModel = new RealisasiModel();
        
        // Cari realisasi bawahan yang menunggu approval
        $userId = session()->get('id');
        $userRole = session()->get('role');
        
        $query = $realisasiModel->select('realisasi.*, users.nama_lengkap as user_name, users.unit_kerja, rhk.nama_rhk, rhk_indikator.indikator')
                                ->join('rhk_indikator', 'rhk_indikator.id = realisasi.rhk_indikator_id')
                                ->join('rhk', 'rhk.id = rhk_indikator.rhk_id')
                                ->join('skp_master', 'skp_master.id = rhk.skp_id')
                                ->join('users', 'users.id = skp_master.user_id')
                                ->where('realisasi.status', 'menunggu_approval');
        
        // Rektor bisa lihat semua, lainnya filter by atasan
        if ($userRole !== 'rektor') {
            $query->where('users.atasan_id', $userId);
        }
        
        $realisasiList = $query->orderBy('realisasi.created_at', 'DESC')->findAll();
        
        $data = [
            'title' => 'Persetujuan Realisasi',
            'realisasiList' => $realisasiList
        ];
        
        return view('realisasi/approval', $data);
    }
}
