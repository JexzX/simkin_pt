<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\NotifikasiModel;

class Approval extends BaseController
{
    public function skpList()
    {
        $skpModel = new SkpModel();
        $userId = session()->get('id');
        $userRole = session()->get('role');
        
        $skpList = $skpModel->select('skp_master.*, users.nama_lengkap as user_name, users.unit_kerja, users.jabatan')
                            ->join('users', 'users.id = skp_master.user_id')
                            ->where('skp_master.status', 'pengajuan')
                            ->where('users.atasan_id', $userId)
                            ->orderBy('skp_master.tanggal_pengajuan', 'DESC')
                            ->findAll();
        
        $data = [
            'title' => 'Persetujuan SKP',
            'skpList' => $skpList
        ];
        
        return view('skp/approval', $data);
    }

    public function approveSkp($id)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->find($id);
        
        if (!$skp) {
            return redirect()->back()->with('error', 'SKP tidak ditemukan');
        }
        
        $catatan = $this->request->getPost('catatan');
        
        $skpModel->update($id, [
            'status' => 'disetujui',
            'catatan_atasan' => $catatan,
            'tanggal_approval' => date('Y-m-d H:i:s')
        ]);
        
        $this->addRiwayatApproval($id, 'pengajuan', 'disetujui');
        
        $notifikasiModel = new NotifikasiModel();
        $notifikasiModel->addNotifikasi(
            $skp['user_id'],
            'SKP Disetujui',
            'SKP Anda telah disetujui oleh ' . session()->get('nama_lengkap'),
            '/skp/detail/' . $id
        );
        
        return redirect()->back()->with('success', 'SKP berhasil disetujui');
    }

    public function rejectSkp($id)
    {
        $skpModel = new SkpModel();
        $skp = $skpModel->find($id);
        
        if (!$skp) {
            return redirect()->back()->with('error', 'SKP tidak ditemukan');
        }
        
        $catatan = $this->request->getPost('catatan');
        
        $skpModel->update($id, [
            'status' => 'draft',
            'catatan_atasan' => $catatan,
            'tanggal_approval' => date('Y-m-d H:i:s')
        ]);
        
        $this->addRiwayatApproval($id, 'pengajuan', 'draft');
        
        $notifikasiModel = new NotifikasiModel();
        $notifikasiModel->addNotifikasi(
            $skp['user_id'],
            'SKP Perlu Revisi',
            'SKP Anda perlu direvisi oleh ' . session()->get('nama_lengkap') . '. Catatan: ' . $catatan,
            '/skp/detail/' . $id
        );
        
        return redirect()->back()->with('success', 'SKP dikembalikan untuk revisi');
    }

    private function addRiwayatApproval($skpId, $dariStatus, $keStatus)
    {
        $db = \Config\Database::connect();
        $db->table('skp_riwayat_approval')->insert([
            'skp_id' => $skpId,
            'dari_status' => $dariStatus,
            'ke_status' => $keStatus,
            'oleh_user_id' => session()->get('id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
