<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSpModel extends Model
{
    protected $table = 'master_sp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_sp', 'nama_sp', 'indikator_iksp', 'tahun', 'created_by'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getAll()
    {
        return $this->orderBy('kode_sp', 'ASC')->findAll();
    }
}