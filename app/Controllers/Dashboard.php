<?php

namespace App\Controllers;

use App\Models\SkpModel;
use App\Models\PeriodeModel;
use App\Models\RealisasiModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('id');
        $role = session()->get('role');
        
        $skpModel = new SkpModel();
        $periodeModel = new PeriodeModel();
        $realisasiModel = new RealisasiModel();
        $userModel = new UserModel();
        
        $periodeAktif = $periodeModel->getActivePeriode();
        
        // Statistik SKP user
        $totalSkp = 0;
        $totalDisetujui = 0;
        $totalDiajukan = 0;
        $totalDraft = 0;
        if ($periodeAktif) {
            $skpUser = $skpModel->where('user_id', $userId)
                                ->where('periode_id', $periodeAktif['id'])
                                ->findAll();
            $totalSkp = count($skpUser);
            foreach ($skpUser as $s) {
                if ($s['status'] == 'disetujui') $totalDisetujui++;
                elseif ($s['status'] == 'pengajuan') $totalDiajukan++;
                elseif ($s['status'] == 'draft') $totalDraft++;
            }
        }
        
        // Approval pending count (untuk atasan)
        $approvalPending = 0;
        if (in_array($role, ['rektor', 'dekan', 'kaprodi', 'super_admin'])) {
            $pendingSkp = $skpModel->getSkpForApproval($userId);
            $approvalPending = count($pendingSkp);
        }
        
        $data = [
            'nama' => session()->get('nama_lengkap'),
            'role' => $role,
            'unit_kerja' => session()->get('unit_kerja'),
            'periodeAktif' => $periodeAktif,
            'totalSkp' => $totalSkp,
            'totalDisetujui' => $totalDisetujui,
            'totalDiajukan' => $totalDiajukan,
            'totalDraft' => $totalDraft,
            'approvalPending' => $approvalPending,
        ];
        
        return view('dashboard/index', $data);
    }
}