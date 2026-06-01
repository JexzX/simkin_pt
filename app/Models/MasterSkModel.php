<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSkModel extends Model
{
    protected $table = 'master_sk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_sk', 'nama_sk', 'sp_id', 'tahun', 'created_by'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getBySp($spId)
    {
        return $this->where('sp_id', $spId)->orderBy('kode_sk', 'ASC')->findAll();
    }
}