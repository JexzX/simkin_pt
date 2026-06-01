<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSkModel extends Model
{
    protected $table = 'master_sk';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_sk', 'nama_sk', 'sp_id', 'tahun', 'created_by'
    ];
    
    protected $useTimestamps = false;

    public function getWithIksk($spId = null)
    {
        $builder = $this->select('master_sk.*, master_sp.kode_sp, master_sp.nama_sp, COUNT(master_iksk.id) as jumlah_iksk')
                        ->join('master_sp', 'master_sp.id = master_sk.sp_id')
                        ->join('master_iksk', 'master_iksk.sk_id = master_sk.id', 'left')
                        ->groupBy('master_sk.id');
        
        if ($spId) {
            $builder->where('master_sk.sp_id', $spId);
        }
        
        return $builder->orderBy('master_sk.kode_sk', 'ASC')->findAll();
    }

    public function getSkBySp($spId)
    {
        return $this->where('sp_id', $spId)->orderBy('kode_sk', 'ASC')->findAll();
    }
}