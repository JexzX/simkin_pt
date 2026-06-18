<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianSkpModel extends Model
{
    protected $table = 'penilaian_skp';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'skp_id', 'nilai_kuantitas', 'nilai_kualitas', 'nilai_waktu',
        'nilai_total', 'predikat', 'catatan_penilai', 'tanggal_penilaian',
        'penilai_id', 'status_penilaian', 'rincian_nilai'
    ];
    
    protected $useTimestamps = false;

    public function getBySkp($skpId)
    {
        return $this->where('skp_id', $skpId)->first();
    }

    public function getPenilaianByAtasan($atasanId)
    {
        $userModel = new UserModel();
        $bawahan = $userModel->getBawahan($atasanId);
        $bawahanIds = array_column($bawahan, 'id');
        
        if (empty($bawahanIds)) {
            return [];
        }
        
        return $this->select('penilaian_skp.*, users.nama_lengkap, users.unit_kerja, skp_master.status as skp_status')
                    ->join('skp_master', 'skp_master.id = penilaian_skp.skp_id')
                    ->join('users', 'users.id = skp_master.user_id')
                    ->whereIn('skp_master.user_id', $bawahanIds)
                    ->findAll();
    }
}