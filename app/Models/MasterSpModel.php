<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSpModel extends Model
{
    protected $table = 'master_sp';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_sp', 'nama_sp', 'indikator_iksp', 'tahun', 'created_by'
    ];
    
    protected $useTimestamps = false;

    public function getWithSk()
    {
        return $this->select('master_sp.*, COUNT(master_sk.id) as jumlah_sk')
                    ->join('master_sk', 'master_sk.sp_id = master_sp.id', 'left')
                    ->groupBy('master_sp.id')
                    ->orderBy('master_sp.kode_sp', 'ASC')
                    ->findAll();
    }

    public function getSpByTahun($tahun)
    {
        return $this->where('tahun', $tahun)->orderBy('kode_sp', 'ASC')->findAll();
    }
}