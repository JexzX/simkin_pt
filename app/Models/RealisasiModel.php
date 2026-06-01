<?php

namespace App\Models;

use CodeIgniter\Model;

class RealisasiModel extends Model
{
    protected $table = 'realisasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'rhk_indikator_id', 'bulan', 'tahun', 'realisasi_kuantitas',
        'realisasi_kualitas', 'realisasi_waktu', 'bukti_file', 'catatan',
        'status', 'tanggal_realisasi'
    ];
    
    protected $useTimestamps = false;

    public function getByIndikator($indikatorId)
    {
        return $this->where('rhk_indikator_id', $indikatorId)
                    ->orderBy('bulan', 'ASC')
                    ->findAll();
    }

    public function getBySkp($skpId)
    {
        return $this->select('realisasi.*, rhk_indikator.rhk_id, rhk.nama_rhk')
                    ->join('rhk_indikator', 'rhk_indikator.id = realisasi.rhk_indikator_id')
                    ->join('rhk', 'rhk.id = rhk_indikator.rhk_id')
                    ->where('rhk.skp_id', $skpId)
                    ->orderBy('realisasi.bulan', 'ASC')
                    ->findAll();
    }

    public function getForApproval($atasanId)
    {
        $userModel = new UserModel();
        $bawahan = $userModel->getBawahan($atasanId);
        $bawahanIds = array_column($bawahan, 'id');
        
        if (empty($bawahanIds)) {
            return [];
        }
        
        return $this->select('realisasi.*, users.nama_lengkap as user_name, rhk.nama_rhk')
                    ->join('rhk_indikator', 'rhk_indikator.id = realisasi.rhk_indikator_id')
                    ->join('rhk', 'rhk.id = rhk_indikator.rhk_id')
                    ->join('skp_master', 'skp_master.id = rhk.skp_id')
                    ->join('users', 'users.id = skp_master.user_id')
                    ->whereIn('skp_master.user_id', $bawahanIds)
                    ->where('realisasi.status', 'menunggu_approval')
                    ->orderBy('realisasi.created_at', 'DESC')
                    ->findAll();
    }

    public function submitApproval($realisasiId, $status, $catatan = null)
    {
        $data = ['status' => $status];
        if ($catatan) {
            $data['catatan'] = $catatan;
        }
        return $this->update($realisasiId, $data);
    }

    public function getProgressBySkp($skpId)
    {
        $rhkModel = new RhkModel();
        $indikatorModel = new RhkIndikatorModel();
        
        $rhkList = $rhkModel->where('skp_id', $skpId)->findAll();
        $progress = [];
        
        foreach ($rhkList as $rhk) {
            $indikator = $indikatorModel->where('rhk_id', $rhk['id'])->first();
            if ($indikator && $rhk['jenis_rhk'] == 'kuantitatif' && $rhk['target_kuantitas'] > 0) {
                $realisasi = $this->selectSum('realisasi_kuantitas')
                                  ->where('rhk_indikator_id', $indikator['id'])
                                  ->where('status', 'disetujui')
                                  ->first();
                $totalRealisasi = $realisasi['realisasi_kuantitas'] ?? 0;
                $progress[$rhk['id']] = [
                    'nama_rhk' => $rhk['nama_rhk'],
                    'target' => $rhk['target_kuantitas'],
                    'realisasi' => $totalRealisasi,
                    'persen' => min(100, round($totalRealisasi / $rhk['target_kuantitas'] * 100, 2))
                ];
            }
        }
        
        return $progress;
    }
}