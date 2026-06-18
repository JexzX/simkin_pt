<?php

namespace App\Models;

use CodeIgniter\Model;

class RhkModel extends Model
{
    protected $table = 'rhk';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'skp_id', 'intervensi_dari_manual', 'intervensi_indikator_id', 'nama_rhk',
        'jenis_rhk', 'klasifikasi', 'target_kuantitas', 'target_satuan',
        'target_kualitas', 'target_waktu'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getBySkp($skpId)
    {
        return $this->where('skp_id', $skpId)->orderBy('created_at', 'ASC')->findAll();
    }

    public function getIntervensiIndikatorDetail($rhkId)
    {
        $db = \Config\Database::connect();
        return $db->table('rhk')
            ->select('rhk_indikator.*')
            ->join('rhk_indikator', 'rhk_indikator.id = rhk.intervensi_indikator_id')
            ->where('rhk.id', $rhkId)
            ->where('rhk.intervensi_indikator_id IS NOT NULL')
            ->get()->getRowArray();
    }
}