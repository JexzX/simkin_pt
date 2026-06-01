<?php

namespace App\Models;

use CodeIgniter\Model;

class RhkIndikatorModel extends Model
{
    protected $table = 'rhk_indikator';
    protected $primaryKey = 'id';
    protected $allowedFields = ['rhk_id', 'indikator', 'target', 'perspektif'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getByRhk($rhkId)
    {
        return $this->where('rhk_id', $rhkId)->findAll();
    }
}