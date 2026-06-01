<?php

namespace App\Models;

use CodeIgniter\Model;

class RhkIndikatorModel extends Model
{
    protected $table = 'rhk_indikator';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'rhk_id', 'indikator', 'target', 'perspektif'
    ];
    
    protected $useTimestamps = false;

    public function getByRhk($rhkId)
    {
        return $this->where('rhk_id', $rhkId)->findAll();
    }

    public function getWithRealisasi($rhkId)
    {
        $indikator = $this->where('rhk_id', $rhkId)->findAll();
        $realisasiModel = new RealisasiModel();
        
        foreach ($indikator as &$ind) {
            $ind['realisasi'] = $realisasiModel->getByIndikator($ind['id']);
        }
        
        return $indikator;
    }
}