<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'judul', 'pesan', 'is_read', 'link'
    ];
    
    protected $useTimestamps = false;

    public function addNotifikasi($userId, $judul, $pesan, $link = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'link' => $link,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    public function getNotifikasi($userId, $limit = 10)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function markAsRead($id, $userId)
    {
        return $this->where('id', $id)
                    ->where('user_id', $userId)
                    ->set(['is_read' => 1])
                    ->update();
    }

    public function markAllRead($userId)
    {
        return $this->where('user_id', $userId)
                    ->set(['is_read' => 1])
                    ->update();
    }
}