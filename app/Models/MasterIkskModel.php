<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterIkskModel extends Model
{
    protected $table = 'master_iksk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_iksk', 'nama_iksk', 'sk_id', 'target_awal', 'pic_unit', 'tahun', 'created_by'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getBySk($skId)
    {
        return $this->where('sk_id', $skId)->orderBy('kode_iksk', 'ASC')->findAll();
    }
}