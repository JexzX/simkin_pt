<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodeModel extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tahun', 'nama_periode', 'tanggal_mulai', 'tanggal_selesai',
        'batas_akhir_pengajuan_skp', 'batas_akhir_realisasi', 'batas_akhir_penilaian', 'is_active'
    ];
    
    protected $useTimestamps = false;

    public function getActivePeriode()
    {
        return $this->where('is_active', 1)->first();
    }

    public function getPeriodeWithSkp($userId)
    {
        return $this->select('periode.*, skp_master.id as skp_id, skp_master.status as skp_status')
                    ->join('skp_master', 'skp_master.periode_id = periode.id AND skp_master.user_id = ' . $userId, 'left')
                    ->where('periode.is_active', 1)
                    ->orWhere('periode.tahun', date('Y'))
                    ->findAll();
    }
}