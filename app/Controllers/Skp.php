<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PeriodeModel;
use App\Models\RhkModel;
use App\Models\RhkIndikatorModel;
use App\Models\MasterIkskModel;

class Skp extends BaseController
{
    public function index()
    {
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        
        $periodeAktif = $periodeModel->getActivePeriode();
        $skpList = $skpModel->getSkpByUser(session()->get('id'), $periodeAktif['id'] ?? null);
        
        $data = [
            'title' => 'Daftar SKP',
            'skpList' => $skpList,
            'periodeAktif' => $periodeAktif
        ];
        
        return view('skp/index', $data);
    }
    
    public function create()
    {
        $periodeModel = new PeriodeModel();
        $periodeAktif = $periodeModel->getActivePeriode();
        
        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Belum ada periode aktif. Hubungi admin.');
        }
        
        // Cek apakah sudah punya SKP di periode ini
        $skpModel = new SkpModel();
        $existing = $skpModel->where('user_id', session()->get('id'))
                             ->where('periode_id', $periodeAktif['id'])
                             ->where('status !=', 'selesai')
                             ->first();
        
        if ($existing) {
            return redirect()->to('/skp/detail/' . $existing['id'])
                             ->with('error', 'Anda sudah memiliki SKP untuk periode ini. Silakan edit SKP yang ada.');
        }
        
        $data = [
            'title' => 'Buat SKP Baru',
            'periode' => $periodeAktif
        ];
        
        return view('skp/create', $data);
    }
    
    public function store()
    {
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        
        $periodeAktif = $periodeModel->getActivePeriode();
        
        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Belum ada periode aktif. Hubungi admin.');
        }
        
        $data = [
            'user_id' => session()->get('id'),
            'periode_id' => $periodeAktif['id'],
            'status' => 'draft',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $skpModel->insert($data);
        $skpId = $skpModel->getInsertID();
        
        return redirect()->to('/skp/detail/' . $skpId)->with('success', 'SKP berhasil dibuat. Silakan tambah RHK.');
    }
    
    public function detail($id)
    {
        $skpModel = new SkpModel();
        $rhkModel = new RhkModel();
        $indikatorModel = new RhkIndikatorModel();
        
        $skp = $skpModel->find($id);
        
        if (!$skp) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        $rhkList = $rhkModel->getBySkp($id);
        
        // Ambil indikator untuk setiap RHK
        foreach ($rhkList as &$rhk) {
            $rhk['indikator'] = $indikatorModel->getByRhk($rhk['id']);
        }
        
        $totalBobot = $rhkModel->hitungTotalBobot($id);
        
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
            'title' => 'Detail SKP',
            'skp' => $skp,
            'rhkList' => $rhkList,
            'totalBobot' => $totalBobot,
            'intervensiList' => $intervensiList,
            'role' => $role,
            'bisaDiajukan' => ($totalBobot == 100 && !empty($rhkList))
        ];
        
        return view('skp/detail', $data);
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
        
        $skp = $skpModel->find($id);
        
        if (!$skp || $skp['user_id'] != session()->get('id')) {
            return redirect()->to('/skp')->with('error', 'SKP tidak ditemukan');
        }
        
        $totalBobot = $rhkModel->hitungTotalBobot($id);
        
        if ($totalBobot != 100) {
            return redirect()->back()->with('error', 'Total bobot RHK harus 100%. Saat ini: ' . $totalBobot . '%');
        }
        
        $rhkList = $rhkModel->getBySkp($id);
        if (empty($rhkList)) {
            return redirect()->back()->with('error', 'Minimal harus ada 1 RHK');
        }
        
        $skpModel->update($id, [
            'status' => 'menunggu_approval',
            'tanggal_pengajuan' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to('/skp/detail/' . $id)->with('success', 'SKP berhasil diajukan. Menunggu persetujuan atasan.');
    }
}