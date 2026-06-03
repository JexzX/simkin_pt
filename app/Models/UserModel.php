<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'password', 'nama_lengkap', 'nip', 'email',
        'unit_kerja', 'jabatan', 'role', 'atasan_id', 'status', 'foto'
    ];
    
    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function getAtasan($userId)
    {
        $user = $this->find($userId);
        if ($user && $user['atasan_id']) {
            return $this->find($user['atasan_id']);
        }
        return null;
    }

    public function getBawahan($userId)
    {
        return $this->where('atasan_id', $userId)->findAll();
    }

    public function getByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function getByUnit($unitKerja)
    {
        return $this->where('unit_kerja', $unitKerja)->findAll();
    }
    
    // Method untuk cek apakah user A adalah atasan dari user B
    public function isAtasanDari($atasanId, $bawahanId)
    {
        $bawahan = $this->find($bawahanId);
        return ($bawahan && $bawahan['atasan_id'] == $atasanId);
    }
}