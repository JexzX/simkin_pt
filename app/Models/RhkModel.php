<?php

namespace App\Models;

use CodeIgniter\Model;

class RhkModel extends Model
{
    protected $table = 'rhk';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'skp_id', 'intervensi_dari_type', 'intervensi_dari_id', 'nama_rhk',
        'jenis_rhk', 'klasifikasi', 'target_kuantitas', 'target_satuan',
        'target_kualitas', 'target_waktu', 'bobot'
    ];
    
    protected $useTimestamps = false;

    public function getRhkWithIndikator($skpId)
    {
        $rhkList = $this->where('skp_id', $skpId)->orderBy('created_at', 'ASC')->findAll();
        $indikatorModel = new RhkIndikatorModel();
        
        foreach ($rhkList as &$rhk) {
            $rhk['indikator'] = $indikatorModel->getByRhk($rhk['id']);
        }
        
        return $rhkList;
    }

    public function getRhkForIntervensi($userId, $role)
    {
        $userModel = new UserModel();
        $skpModel = new SkpModel();
        
        $atasanId = $userModel->find($userId)['atasan_id'];
        if (!$atasanId) {
            return [];
        }
        
        $skpAtasan = $skpModel->where('user_id', $atasanId)
                              ->where('status', 'disetujui')
                              ->first();
        
        if (!$skpAtasan) {
            return [];
        }
        
        return $this->where('skp_id', $skpAtasan['id'])->findAll();
    }

    public function updateBobot($skpId, $bobotList)
    {
        foreach ($bobotList as $rhkId => $bobot) {
            $this->update($rhkId, ['bobot' => $bobot]);
        }
        return true;
    }

    public function hitungTotalBobot($skpId)
    {
        $total = $this->selectSum('bobot')->where('skp_id', $skpId)->first();
        return $total['bobot'] ?? 0;
    }
}