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
    protected $returnType = 'array';

    public function getBySkp($skpId)
    {
        return $this->where('skp_id', $skpId)->orderBy('created_at', 'ASC')->findAll();
    }

    public function hitungTotalBobot($skpId)
    {
        $result = $this->selectSum('bobot')->where('skp_id', $skpId)->first();
        return $result['bobot'] ?? 0;
    }
}