<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterIkskModel extends Model
{
    protected $table = 'master_iksk';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_iksk', 'nama_iksk', 'sk_id', 'target_awal', 'pic_unit', 'tahun', 'created_by'
    ];
    
    protected $useTimestamps = false;

    public function getWithSk($skId = null)
    {
        $builder = $this->select('master_iksk.*, master_sk.kode_sk, master_sk.nama_sk, master_sp.kode_sp')
                        ->join('master_sk', 'master_sk.id = master_iksk.sk_id')
                        ->join('master_sp', 'master_sp.id = master_sk.sp_id');
        
        if ($skId) {
            $builder->where('master_iksk.sk_id', $skId);
        }
        
        return $builder->orderBy('master_iksk.kode_iksk', 'ASC')->findAll();
    }

    public function getByPicUnit($unitKerja)
    {
        return $this->where('pic_unit', $unitKerja)
                    ->where('tahun', date('Y'))
                    ->findAll();
    }
}