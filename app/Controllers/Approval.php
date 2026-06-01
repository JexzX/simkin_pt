<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\NotifikasiModel;

class Approval extends BaseController
{
    public function skpList()
    {
        $skpModel = new SkpModel();
        $skpList = $skpModel->getSkpForApproval(session()->get('id'));
        
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
        $skpModel->submitApproval($id, 'disetujui', $catatan);
        
        // Tambah riwayat
        $this->addRiwayatApproval($id, 'menunggu_approval', 'disetujui');
        
        // Notifikasi ke pembuat SKP
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
        $skpModel->submitApproval($id, 'ditolak', $catatan);
        
        // Tambah riwayat
        $this->addRiwayatApproval($id, 'menunggu_approval', 'ditolak');
        
        // Notifikasi ke pembuat SKP
        $notifikasiModel = new NotifikasiModel();
        $notifikasiModel->addNotifikasi(
            $skp['user_id'],
            'SKP Ditolak',
            'SKP Anda ditolak oleh ' . session()->get('nama_lengkap') . '. Catatan: ' . $catatan,
            '/skp/detail/' . $id
        );
        
        return redirect()->back()->with('success', 'SKP ditolak');
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