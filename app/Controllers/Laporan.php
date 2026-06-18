<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PeriodeModel;
use App\Models\UserModel;
use App\Models\RealisasiModel;
use App\Models\PenilaianSkpModel;

class Laporan extends BaseController
{
    public function skp()
    {
        $periodeModel = new PeriodeModel();
        $userModel = new UserModel();
        $periodeList = $periodeModel->findAll();
        
        $periodeId = $this->request->getGet('periode_id');
        $unitKerja = $this->request->getGet('unit_kerja');
        $filterBawahan = $this->request->getGet('bawahan_saya');
        
        $skpModel = new SkpModel();
        $query = $skpModel->select('skp_master.*, users.nama_lengkap, users.unit_kerja, users.jabatan')
                          ->join('users', 'users.id = skp_master.user_id');
        
        if ($periodeId) {
            $query->where('skp_master.periode_id', $periodeId);
        }
        
        if ($unitKerja) {
            $query->where('users.unit_kerja', $unitKerja);
        }
        
        $userId = session()->get('id');
        if ($filterBawahan) {
            $bawahanIds = $userModel->where('atasan_id', $userId)->findColumn('id') ?? [];
            if (!empty($bawahanIds)) {
                $query->whereIn('skp_master.user_id', $bawahanIds);
            } else {
                $query->where('1=0');
            }
        }
        
        $skpList = $query->orderBy('users.unit_kerja', 'ASC')->findAll();
        
        $data = [
            'title' => 'Laporan SKP',
            'skpList' => $skpList,
            'periodeList' => $periodeList,
            'periodeId' => $periodeId,
            'unitKerja' => $unitKerja,
            'filterBawahan' => $filterBawahan
        ];
        
        return view('laporan/skp', $data);
    }

    public function realisasi()
    {
        $periodeModel = new PeriodeModel();
        $userModel = new UserModel();
        $periodeList = $periodeModel->findAll();
        
        $periodeId = $this->request->getGet('periode_id');
        $bulan = $this->request->getGet('bulan');
        $filterBawahan = $this->request->getGet('bawahan_saya');
        
        $realisasiModel = new RealisasiModel();
        $query = $realisasiModel->select('realisasi.*, users.nama_lengkap, users.unit_kerja, rhk.nama_rhk, skp_master.id as skp_id')
                                ->join('rhk_indikator', 'rhk_indikator.id = realisasi.rhk_indikator_id')
                                ->join('rhk', 'rhk.id = rhk_indikator.rhk_id')
                                ->join('skp_master', 'skp_master.id = rhk.skp_id')
                                ->join('users', 'users.id = skp_master.user_id');
        
        if ($periodeId) {
            $query->where('skp_master.periode_id', $periodeId);
        }
        
        if ($bulan) {
            $query->where('realisasi.bulan', $bulan);
        }
        
        $bulan = $bulan ?: date('n');
        
        $userId = session()->get('id');
        if ($filterBawahan) {
            $bawahanIds = $userModel->where('atasan_id', $userId)->findColumn('id') ?? [];
            if (!empty($bawahanIds)) {
                $query->whereIn('skp_master.user_id', $bawahanIds);
            } else {
                $query->where('1=0');
            }
        }
        
        $realisasiList = $query->orderBy('users.unit_kerja', 'ASC')->findAll();
        
        $data = [
            'title' => 'Laporan Realisasi',
            'realisasiList' => $realisasiList,
            'periodeList' => $periodeList,
            'periodeId' => $periodeId,
            'bulan' => $bulan,
            'filterBawahan' => $filterBawahan
        ];
        
        return view('laporan/realisasi', $data);
    }

    public function export($type)
    {
        $periodeId = $this->request->getGet('periode_id');
        
        if ($type == 'skp') {
            return $this->exportSkp($periodeId);
        } elseif ($type == 'realisasi') {
            return $this->exportRealisasi($periodeId);
        }
        
        return redirect()->back()->with('error', 'Tipe laporan tidak valid');
    }

    private function exportSkp($periodeId)
    {
        $skpModel = new SkpModel();
        $skpList = $skpModel->select('skp_master.*, users.nama_lengkap, users.unit_kerja, users.jabatan, periode.nama_periode')
                            ->join('users', 'users.id = skp_master.user_id')
                            ->join('periode', 'periode.id = skp_master.periode_id')
                            ->where('skp_master.periode_id', $periodeId)
                            ->findAll();
        
        // Load Excel library or simple CSV export
        $filename = 'laporan_skp_' . date('Ymd_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama', 'Unit Kerja', 'Jabatan', 'Status', 'Nilai Akhir', 'Predikat']);
        
        $no = 1;
        foreach ($skpList as $skp) {
            fputcsv($output, [
                $no++,
                $skp['nama_lengkap'],
                $skp['unit_kerja'],
                $skp['jabatan'],
                $skp['status'],
                $skp['nilai_akhir'] ?? '-',
                $skp['predikat'] ?? '-'
            ]);
        }
        
        fclose($output);
        exit();
    }

    private function exportRealisasi($periodeId)
    {
        $realisasiModel = new RealisasiModel();
        $realisasiList = $realisasiModel->select('realisasi.*, users.nama_lengkap, users.unit_kerja, rhk.nama_rhk, periode.nama_periode')
                                        ->join('rhk_indikator', 'rhk_indikator.id = realisasi.rhk_indikator_id')
                                        ->join('rhk', 'rhk.id = rhk_indikator.rhk_id')
                                        ->join('skp_master', 'skp_master.id = rhk.skp_id')
                                        ->join('users', 'users.id = skp_master.user_id')
                                        ->join('periode', 'periode.id = skp_master.periode_id')
                                        ->where('skp_master.periode_id', $periodeId)
                                        ->findAll();
        
        $filename = 'laporan_realisasi_' . date('Ymd_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama', 'Unit Kerja', 'RHK', 'Bulan', 'Realisasi', 'Status']);
        
        $no = 1;
        foreach ($realisasiList as $realisasi) {
            $realisasiValue = $realisasi['realisasi_kuantitas'] ?? $realisasi['realisasi_kualitas'] ?? '-';
            fputcsv($output, [
                $no++,
                $realisasi['nama_lengkap'],
                $realisasi['unit_kerja'],
                $realisasi['nama_rhk'],
                $realisasi['bulan'],
                $realisasiValue,
                $realisasi['status']
            ]);
        }
        
        fclose($output);
        exit();
    }
}