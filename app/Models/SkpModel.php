<?php

namespace App\Models;

use CodeIgniter\Model;

class SkpModel extends Model
{
    protected $table = 'skp_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'periode_id', 'tanggal_mulai', 'tanggal_selesai', 'pendekatan', 'status', 'catatan_atasan',
        'nilai_akhir', 'predikat', 'tanggal_pengajuan', 'tanggal_approval'
    ];
    
    protected $useTimestamps = false;

    public function getSkpWithDetails($skpId)
    {
        return $this->select('skp_master.*, users.nama_lengkap as user_name, users.unit_kerja, periode.tahun, periode.nama_periode')
                    ->join('users', 'users.id = skp_master.user_id')
                    ->join('periode', 'periode.id = skp_master.periode_id')
                    ->where('skp_master.id', $skpId)
                    ->first();
    }

    public function getSkpByUser($userId, $periodeId = null)
    {
        $builder = $this->where('user_id', $userId);
        if ($periodeId) {
            $builder->where('periode_id', $periodeId);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    // METHOD INI YANG DIPERBAIKI
    public function getSkpForApproval($atasanId)
    {
        return $this->select('skp_master.*, users.nama_lengkap as user_name, users.unit_kerja, users.jabatan')
                    ->join('users', 'users.id = skp_master.user_id')
                    ->where('skp_master.status', 'pengajuan')
                    ->where('users.atasan_id', $atasanId)
                    ->orderBy('skp_master.tanggal_pengajuan', 'DESC')
                    ->findAll();
    }

    public function submitApproval($skpId, $status, $catatan = null)
    {
        $data = [
            'status' => $status,
            'catatan_atasan' => $catatan,
            'tanggal_approval' => date('Y-m-d H:i:s')
        ];
        
        if ($status == 'disetujui') {
            $data['tanggal_approval'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($skpId, $data);
    }
}