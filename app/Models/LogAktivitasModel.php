<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAktivitasModel extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'aksi', 'deskripsi', 'ip_address'
    ];
    
    protected $useTimestamps = false;

    public function getLogByUser($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getLogByDate($startDate, $endDate)
    {
        return $this->where('DATE(created_at) >=', $startDate)
                    ->where('DATE(created_at) <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}