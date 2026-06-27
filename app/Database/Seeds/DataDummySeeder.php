<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataDummySeeder extends Seeder
{
    public function run()
    {
        $pw = md5('123');

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  1. USERS (18)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $raw = [
            // [username, role, atasan_username, unit_kerja, jabatan]
            ['admin',           'super_admin',     null,   'UIN Salatiga',                              'Super Administrator'],
            ['admin_perencana', 'admin_perencana', null,   'BIRO',                                       'Admin Perencana'],
            ['rektor',          'rektor',          null,   'UIN Salatiga',                              'Rektor'],
            ['dekan_tarbiyah',  'dekan',  'rektor',           'Fakultas Tarbiyah dan Ilmu Keguruan',    'Dekan'],
            ['kaprodi_tarbiyah','kaprodi','dekan_tarbiyah',   'Fakultas Tarbiyah dan Ilmu Keguruan',    'Kaprodi'],
            ['dosen_tarbiyah',  'dosen',  'kaprodi_tarbiyah', 'Fakultas Tarbiyah dan Ilmu Keguruan',    'Dosen'],
            ['dekan_syariah',   'dekan',  'rektor',           'Fakultas Syariah',                      'Dekan'],
            ['kaprodi_syariah', 'kaprodi','dekan_syariah',    'Fakultas Syariah',                      'Kaprodi'],
            ['dosen_syariah',   'dosen',  'kaprodi_syariah',  'Fakultas Syariah',                      'Dosen'],
            ['dekan_ushuluddin','dekan',  'rektor',           'Fakultas Ushuluddin, Adab, dan Humaniora','Dekan'],
            ['kaprodi_ushuluddin','kaprodi','dekan_ushuluddin','Fakultas Ushuluddin, Adab, dan Humaniora','Kaprodi'],
            ['dosen_ushuluddin','dosen',  'kaprodi_ushuluddin','Fakultas Ushuluddin, Adab, dan Humaniora','Dosen'],
            ['dekan_ekonomi',   'dekan',  'rektor',           'Fakultas Ekonomi dan Bisnis Islam',     'Dekan'],
            ['kaprodi_ekonomi', 'kaprodi','dekan_ekonomi',    'Fakultas Ekonomi dan Bisnis Islam',     'Kaprodi'],
            ['dosen_ekonomi',   'dosen',  'kaprodi_ekonomi',  'Fakultas Ekonomi dan Bisnis Islam',     'Dosen'],
            ['dekan_dakwah',    'dekan',  'rektor',           'Fakultas Dakwah',                       'Dekan'],
            ['kaprodi_dakwah',  'kaprodi','dekan_dakwah',     'Fakultas Dakwah',                       'Kaprodi'],
            ['dosen_dakwah',    'dosen',  'kaprodi_dakwah',   'Fakultas Dakwah',                       'Dosen'],
            ['dekan_saintek',   'dekan',  'rektor',           'Fakultas Sains dan Teknologi',          'Dekan'],
            ['kaprodi_saintek', 'kaprodi','dekan_saintek',    'Fakultas Sains dan Teknologi',          'Kaprodi'],
            ['dosen_saintek',   'dosen',  'kaprodi_saintek',  'Fakultas Sains dan Teknologi',          'Dosen'],
        ];

        $uid = [];
        foreach ($raw as $r) {
            $this->db->table('users')->insert([
                'username'    => $r[0],
                'password'    => $pw,
                'nama_lengkap'=> $this->namaUser($r[0]),
                'role'        => $r[1],
                'unit_kerja'  => $r[3],
                'jabatan'     => $r[4],
                'atasan_id'   => $r[2] ? null : null, // akan diupdate
                'status'      => 'aktif'
            ]);
            $uid[$r[0]] = $this->db->insertID();
        }
        // relasi atasan
        foreach ($raw as $r) {
            if ($r[2] && isset($uid[$r[2]])) {
                $this->db->table('users')->where('username', $r[0])->update(['atasan_id' => $uid[$r[2]]]);
            }
        }
        echo "21 users created.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  2. PERIODE
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->db->table('periode')->insert([
            'tahun' => 2026, 'nama_periode' => 'Tahun 2026',
            'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
            'batas_akhir_pengajuan_skp' => '2026-02-28',
            'batas_akhir_realisasi' => '2026-12-31',
            'batas_akhir_penilaian' => '2027-01-15',
            'is_active' => 1
        ]);
        echo "Periode created.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  3. MASTER UNIT
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $units = [
            'UIN Salatiga',
            'BIRO',
            'LPM',
            'LP2M',
            'Fakultas Tarbiyah dan Ilmu Keguruan',
            'Fakultas Ekonomi dan Bisnis Islam',
            'Fakultas Dakwah',
            'Fakultas Syariah',
            'Fakultas Ushuluddin, Adab, dan Humaniora',
            'Fakultas Sains dan Teknologi',
        ];
        foreach ($units as $nu) {
            $this->db->table('master_unit')->insert(['nama_unit' => $nu]);
        }
        echo "10 master units created.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  4. REKTOR SKP (auto-approved)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $rid = $uid['rektor'];
        $this->db->table('skp_master')->insert([
            'user_id' => $rid, 'periode_id' => 1,
            'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
            'pendekatan' => 'kuantitatif', 'status' => 'disetujui',
            'catatan_atasan' => 'Disetujui otomatis (Rektor)',
            'tanggal_pengajuan' => '2026-01-15 08:00:00',
            'tanggal_approval' => '2026-01-15 08:00:01'
        ]);
        $skp['rektor'] = $this->db->insertID();

        // ---- RHK 1: Renstra ----
        $this->db->table('rhk')->insert([
            'skp_id' => $skp['rektor'],
            'nama_rhk' => 'Menyusun Rencana Strategis UIN Salatiga 2026-2030',
            'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama',
            'intervensi_dari_manual' => 'Mengacu pada Renstra Kemenag 2025-2029 dan Visi UIN Salatiga'
        ]);
        $rhk['rektor_1'] = $this->db->insertID();

        $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk['rektor_1'], 'indikator' => 'Dokumen Renstra disahkan Senat Universitas', 'target' => 'Juni 2026', 'aspek' => 'Kualitas']);
        $ind['rektor_1a'] = $this->db->insertID();
        $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk['rektor_1'], 'indikator' => 'Renstra selaras dengan kebijakan Kementerian Agama', 'target' => '100%', 'aspek' => 'Kualitas']);
        $ind['rektor_1b'] = $this->db->insertID();

        // ---- RHK 2: Akreditasi ----
        $this->db->table('rhk')->insert([
            'skp_id' => $skp['rektor'],
            'nama_rhk' => 'Meningkatkan Peringkat Akreditasi Institusi',
            'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama',
            'intervensi_dari_manual' => 'Mengacu pada target akreditasi institusi tahun 2026'
        ]);
        $rhk['rektor_2'] = $this->db->insertID();

        $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk['rektor_2'], 'indikator' => 'Nilai Akreditasi meningkat ke predikat Unggul', 'target' => 'Predikat Unggul', 'aspek' => 'Kuantitas']);
        $ind['rektor_2a'] = $this->db->insertID();
        $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk['rektor_2'], 'indikator' => 'Dokumen LED dan LKPS disusun lengkap', 'target' => 'September 2026', 'aspek' => 'Kualitas']);
        $ind['rektor_2b'] = $this->db->insertID();
        echo "SKP Rektor: 2 RHK, 4 indikator.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  4. DEKAN (3 approved + 2 draft)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $dekanApproved = [
            'dekan_tarbiyah' => ['Renstra Fakultas Tarbiyah dan Ilmu Keguruan 2026-2030', 'renstra'],
            'dekan_dakwah'   => ['Dokumen Akreditasi Prodi Fakultas Dakwah', 'akreditasi'],
            'dekan_syariah'  => ['Renstra Fakultas Syariah 2026-2030', 'renstra'],
            'dekan_saintek'  => ['Dokumen Akreditasi Prodi Fakultas Sains dan Teknologi', 'akreditasi'],
        ];
        $dekanDraft = [
            'dekan_ushuluddin' => 'Dokumen Akreditasi Fakultas Ushuluddin, Adab, dan Humaniora',
            'dekan_ekonomi'    => 'Dokumen Akreditasi Fakultas Ekonomi dan Bisnis Islam',
        ];

        foreach ($dekanApproved as $usr => $cfg) {
            $status = ($usr === 'dekan_syariah') ? 'pengajuan' : 'disetujui';
            $this->db->table('skp_master')->insert([
                'user_id' => $uid[$usr], 'periode_id' => 1,
                'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
                'pendekatan' => 'kuantitatif', 'status' => $status,
                'tanggal_pengajuan' => '2026-01-20 09:00:00',
                'catatan_atasan' => $status === 'disetujui' ? 'SKP disetujui' : null,
                'tanggal_approval' => $status === 'disetujui' ? '2026-01-21 10:00:00' : null,
            ]);
            $skp[$usr] = $this->db->insertID();

            $indAtas = $cfg[1] === 'renstra' ? $ind['rektor_1a'] : $ind['rektor_2a'];
            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $indAtas,
                'nama_rhk' => $cfg[0],
                'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama'
            ]);
            $rhk[$usr] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'Dokumen selesai tepat waktu dan sesuai standar', 'target' => 'Mei 2026', 'aspek' => 'Kualitas']);
            $ind[$usr.'_a'] = $this->db->insertID();
            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'Dokumen mendapat pengesahan pimpinan Fakultas', 'target' => 'Mei 2026', 'aspek' => 'Kualitas']);
            $ind[$usr.'_b'] = $this->db->insertID();
            echo "  $usr: 1 RHK, 2 indikator ($status)\n";
        }

        foreach ($dekanDraft as $usr => $namaRhk) {
            $this->db->table('skp_master')->insert([
                'user_id' => $uid[$usr], 'periode_id' => 1,
                'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
                'pendekatan' => 'kuantitatif', 'status' => 'draft'
            ]);
            $skp[$usr] = $this->db->insertID();

            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $ind['rektor_2a'],
                'nama_rhk' => $namaRhk,
                'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama'
            ]);
            $rhk[$usr] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'Dokumen akreditasi lengkap sesuai standar BAN-PT', 'target' => 'Agustus 2026', 'aspek' => 'Kualitas']);
            $ind[$usr.'_a'] = $this->db->insertID();
            echo "  $usr: 1 RHK, 1 indikator (draft)\n";
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  5. KAPRODI (2 approved + 1 pengajuan + 2 draft)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $kaprodiApproved = [
            'kaprodi_tarbiyah' => 'dekan_tarbiyah',
            'kaprodi_dakwah'   => 'dekan_dakwah',
            'kaprodi_saintek'  => 'dekan_saintek',
        ];
        $kaprodiPengajuan = ['kaprodi_syariah' => 'dekan_syariah'];
        $kaprodiDraft = [
            'kaprodi_ushuluddin' => 'dekan_ushuluddin',
            'kaprodi_ekonomi'    => 'dekan_ekonomi',
        ];

        foreach ($kaprodiApproved as $usr => $atasan) {
            $this->db->table('skp_master')->insert([
                'user_id' => $uid[$usr], 'periode_id' => 1,
                'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
                'pendekatan' => 'kuantitatif', 'status' => 'disetujui',
                'tanggal_pengajuan' => '2026-02-01 08:00:00',
                'catatan_atasan' => 'SKP disetujui',
                'tanggal_approval' => '2026-02-02 09:00:00'
            ]);
            $skp[$usr] = $this->db->insertID();

            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $ind[$atasan.'_a'],
                'nama_rhk' => 'Mengembangkan Kurikulum Berbasis OBE',
                'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama'
            ]);
            $rhk[$usr] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'Kurikulum OBE tersusun untuk seluruh prodi', 'target' => 'Juli 2026', 'aspek' => 'Kuantitas']);
            $ind[$usr.'_a'] = $this->db->insertID();
            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'Dokumen kurikulum mendapat persetujuan Senat Fakultas', 'target' => 'Juli 2026', 'aspek' => 'Kualitas']);
            $ind[$usr.'_b'] = $this->db->insertID();
            echo "  $usr: 1 RHK, 2 indikator (disetujui)\n";
        }

        foreach ($kaprodiPengajuan as $usr => $atasan) {
            $this->db->table('skp_master')->insert([
                'user_id' => $uid[$usr], 'periode_id' => 1,
                'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
                'pendekatan' => 'kuantitatif', 'status' => 'pengajuan',
                'tanggal_pengajuan' => '2026-03-01 08:00:00'
            ]);
            $skp[$usr] = $this->db->insertID();

            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $ind[$atasan.'_a'],
                'nama_rhk' => 'Menyusun RPS dan Bahan Ajar Berbasis Teknologi',
                'jenis_rhk' => 'kualitatif', 'klasifikasi' => 'utama'
            ]);
            $rhk[$usr] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'RPS tersusun untuk seluruh mata kuliah', 'target' => '100%', 'aspek' => 'Kuantitas']);
            $ind[$usr.'_a'] = $this->db->insertID();
            echo "  $usr: 1 RHK, 1 indikator (pengajuan)\n";
        }

        foreach ($kaprodiDraft as $usr => $atasan) {
            $this->db->table('skp_master')->insert([
                'user_id' => $uid[$usr], 'periode_id' => 1,
                'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
                'pendekatan' => 'kuantitatif', 'status' => 'draft'
            ]);
            $skp[$usr] = $this->db->insertID();

            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $ind[$atasan.'_a'],
                'nama_rhk' => 'Meningkatkan Kualitas Pembelajaran dan Luaran',
                'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama'
            ]);
            $rhk[$usr] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr], 'indikator' => 'IPK lulusan minimal 3,25', 'target' => '3,25', 'aspek' => 'Kuantitas']);
            $ind[$usr.'_a'] = $this->db->insertID();
            echo "  $usr: 1 RHK, 1 indikator (draft)\n";
        }

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  6. DOSEN (2 approved, 3 without SKP)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $dosenApproved = [
            'dosen_tarbiyah' => 'kaprodi_tarbiyah',
            'dosen_dakwah'   => 'kaprodi_dakwah',
            'dosen_saintek'  => 'kaprodi_saintek',
        ];

        foreach ($dosenApproved as $usr => $atasan) {
            $this->db->table('skp_master')->insert([
                'user_id' => $uid[$usr], 'periode_id' => 1,
                'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-12-31',
                'pendekatan' => 'kuantitatif', 'status' => 'disetujui',
                'tanggal_pengajuan' => '2026-02-15 08:00:00',
                'catatan_atasan' => 'SKP disetujui',
                'tanggal_approval' => '2026-02-16 09:00:00'
            ]);
            $skp[$usr] = $this->db->insertID();

            // RHK 1: Penelitian
            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $ind[$atasan.'_a'],
                'nama_rhk' => 'Melaksanakan Penelitian dan Publikasi Ilmiah',
                'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'utama'
            ]);
            $rhk[$usr.'_1'] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr.'_1'], 'indikator' => 'Jurnal terindeks Scopus/Q2', 'target' => '1 artikel', 'aspek' => 'Kuantitas']);
            $ind[$usr.'_1a'] = $this->db->insertID();
            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr.'_1'], 'indikator' => 'Proses penelitian sesuai roadmap prodi', 'target' => '100%', 'aspek' => 'Kualitas']);
            $ind[$usr.'_1b'] = $this->db->insertID();

            // RHK 2: Pengabdian
            $this->db->table('rhk')->insert([
                'skp_id' => $skp[$usr],
                'intervensi_indikator_id' => $ind[$atasan.'_a'],
                'nama_rhk' => 'Melaksanakan Pengabdian kepada Masyarakat',
                'jenis_rhk' => 'kuantitatif', 'klasifikasi' => 'penunjang'
            ]);
            $rhk[$usr.'_2'] = $this->db->insertID();

            $this->db->table('rhk_indikator')->insert(['rhk_id' => $rhk[$usr.'_2'], 'indikator' => 'Kegiatan PkM terlaksana tepat sasaran', 'target' => '2 kegiatan', 'aspek' => 'Kuantitas']);
            $ind[$usr.'_2a'] = $this->db->insertID();
            echo "  $usr: 2 RHK, 3 indikator (disetujui)\n";
        }

        echo "SKP dibuat untuk 16 user. 3 dosen (syariah, ushuluddin, ekonomi) belum punya SKP.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  7. REALISASI (~70 entries)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $bulan = [2, 3, 4, 5];
        $realCount = 0;

        // Rektor: 4 indikator × Feb-Mar-Apr-Mei
        foreach (['rektor_1a','rektor_1b','rektor_2a','rektor_2b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(75, 100),
                    'realisasi_kualitas' => rand(80, 100),
                    'realisasi_waktu' => rand(1, 30),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-25 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Dekan Tarbiyah (disetujui): 2 indikator
        foreach (['dekan_tarbiyah_a','dekan_tarbiyah_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(80, 100),
                    'realisasi_kualitas' => rand(85, 100),
                    'realisasi_waktu' => rand(1, 20),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-26 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Dekan Dakwah (disetujui): 2 indikator
        foreach (['dekan_dakwah_a','dekan_dakwah_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(70, 95),
                    'realisasi_kualitas' => rand(75, 95),
                    'realisasi_waktu' => rand(1, 25),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-27 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Dekan Syariah (pengajuan - status realisasi: menunggu_approval): 2 indikator
        foreach (['dekan_syariah_a','dekan_syariah_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(60, 90),
                    'realisasi_kualitas' => rand(70, 90),
                    'realisasi_waktu' => rand(1, 28),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => $b <= 3 ? 'disetujui' : 'menunggu_approval',
                    'tanggal_realisasi' => sprintf('2026-%02d-28 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Kaprodi Tarbiyah (disetujui): 2 indikator
        foreach (['kaprodi_tarbiyah_a','kaprodi_tarbiyah_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(80, 100),
                    'realisasi_kualitas' => rand(85, 100),
                    'realisasi_waktu' => rand(1, 15),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-20 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Kaprodi Dakwah (disetujui): 2 indikator
        foreach (['kaprodi_dakwah_a','kaprodi_dakwah_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(75, 100),
                    'realisasi_kualitas' => rand(80, 100),
                    'realisasi_waktu' => rand(1, 18),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-22 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Kaprodi Syariah (pengajuan): 1 indikator, 4 bulan
        foreach ($bulan as $b) {
            $this->db->table('realisasi')->insert([
                'rhk_indikator_id' => $ind['kaprodi_syariah_a'],
                'bulan' => $b, 'tahun' => 2026,
                'realisasi_kuantitas' => rand(50, 80),
                'realisasi_kualitas' => rand(60, 85),
                'realisasi_waktu' => rand(1, 30),
                'catatan' => 'Realisasi bulan ' . $b,
                'status' => $b <= 3 ? 'disetujui' : 'menunggu_approval',
                'tanggal_realisasi' => sprintf('2026-%02d-25 10:00:00', $b)
            ]);
            $realCount++;
        }

        // Dosen Tarbiyah (disetujui): 3 indikator × 4 bulan
        foreach (['dosen_tarbiyah_1a','dosen_tarbiyah_1b','dosen_tarbiyah_2a'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(85, 100),
                    'realisasi_kualitas' => rand(85, 100),
                    'realisasi_waktu' => rand(1, 10),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-18 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Dosen Dakwah (disetujui): 3 indikator × 4 bulan
        foreach (['dosen_dakwah_1a','dosen_dakwah_1b','dosen_dakwah_2a'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(80, 100),
                    'realisasi_kualitas' => rand(80, 100),
                    'realisasi_waktu' => rand(1, 15),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-19 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Dekan Saintek (disetujui): 2 indikator
        foreach (['dekan_saintek_a','dekan_saintek_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(75, 100),
                    'realisasi_kualitas' => rand(80, 100),
                    'realisasi_waktu' => rand(1, 20),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-21 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Kaprodi Saintek (disetujui): 2 indikator
        foreach (['kaprodi_saintek_a','kaprodi_saintek_b'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(75, 100),
                    'realisasi_kualitas' => rand(80, 100),
                    'realisasi_waktu' => rand(1, 18),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-22 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        // Dosen Saintek (disetujui): 3 indikator × 4 bulan
        foreach (['dosen_saintek_1a','dosen_saintek_1b','dosen_saintek_2a'] as $ik) {
            foreach ($bulan as $b) {
                $this->db->table('realisasi')->insert([
                    'rhk_indikator_id' => $ind[$ik],
                    'bulan' => $b, 'tahun' => 2026,
                    'realisasi_kuantitas' => rand(80, 100),
                    'realisasi_kualitas' => rand(80, 100),
                    'realisasi_waktu' => rand(1, 12),
                    'catatan' => 'Realisasi bulan ' . $b,
                    'status' => 'disetujui',
                    'tanggal_realisasi' => sprintf('2026-%02d-19 10:00:00', $b)
                ]);
                $realCount++;
            }
        }

        echo "Realisasi: $realCount entries created.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  8. APPROVAL HISTORY (for approved SKPs)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $this->db->table('skp_riwayat_approval')->insert([
            'skp_id' => $skp['rektor'], 'oleh_user_id' => $uid['rektor'],
            'dari_status' => 'draft', 'ke_status' => 'disetujui',
            'catatan' => 'Auto-approve Rektor', 'created_at' => '2026-01-15 08:00:01'
        ]);
        $approvalCount = 1;

        foreach (['dekan_tarbiyah' => 'rektor', 'dekan_dakwah' => 'rektor'] as $d => $at) {
            if (isset($skp[$d])) {
                $this->db->table('skp_riwayat_approval')->insert([
                    'skp_id' => $skp[$d], 'oleh_user_id' => $uid[$at],
                    'dari_status' => 'pengajuan', 'ke_status' => 'disetujui',
                    'catatan' => 'SKP disetujui', 'created_at' => '2026-01-21 10:00:00'
                ]);
                $approvalCount++;
            }
        }

        foreach (['kaprodi_tarbiyah' => 'dekan_tarbiyah', 'kaprodi_dakwah' => 'dekan_dakwah'] as $k => $at) {
            $this->db->table('skp_riwayat_approval')->insert([
                'skp_id' => $skp[$k], 'oleh_user_id' => $uid[$at],
                'dari_status' => 'pengajuan', 'ke_status' => 'disetujui',
                'catatan' => 'SKP disetujui', 'created_at' => '2026-02-02 09:00:00'
            ]);
            $approvalCount++;
        }

        foreach (['dosen_tarbiyah' => 'kaprodi_tarbiyah', 'dosen_dakwah' => 'kaprodi_dakwah', 'dosen_saintek' => 'kaprodi_saintek'] as $d => $at) {
            $this->db->table('skp_riwayat_approval')->insert([
                'skp_id' => $skp[$d], 'oleh_user_id' => $uid[$at],
                'dari_status' => 'pengajuan', 'ke_status' => 'disetujui',
                'catatan' => 'SKP disetujui', 'created_at' => '2026-02-16 09:00:00'
            ]);
            $approvalCount++;
        }

        // SAINTEK approvals
        foreach (['dekan_saintek' => 'rektor'] as $d => $at) {
            if (isset($skp[$d])) {
                $this->db->table('skp_riwayat_approval')->insert([
                    'skp_id' => $skp[$d], 'oleh_user_id' => $uid[$at],
                    'dari_status' => 'pengajuan', 'ke_status' => 'disetujui',
                    'catatan' => 'SKP disetujui', 'created_at' => '2026-01-21 10:00:00'
                ]);
                $approvalCount++;
            }
        }

        foreach (['kaprodi_saintek' => 'dekan_saintek'] as $k => $at) {
            $this->db->table('skp_riwayat_approval')->insert([
                'skp_id' => $skp[$k], 'oleh_user_id' => $uid[$at],
                'dari_status' => 'pengajuan', 'ke_status' => 'disetujui',
                'catatan' => 'SKP disetujui', 'created_at' => '2026-02-02 09:00:00'
            ]);
            $approvalCount++;
        }

        echo "Approval history: $approvalCount records.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  9. PENILAIAN (for approved SKPs with realisasi)
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        $penilaian = [
            'dekan_tarbiyah' => ['penilai' => 'rektor', 'suffixes' => ['_a','_b']],
            'kaprodi_tarbiyah' => ['penilai' => 'dekan_tarbiyah', 'suffixes' => ['_a','_b']],
            'dosen_tarbiyah' => ['penilai' => 'kaprodi_tarbiyah', 'suffixes' => ['_1a','_1b','_2a']],
        ];

        $penilaianCount = 0;
        foreach ($penilaian as $target => $cfg) {
            $skor = [];
            $rincian = [];
            foreach ($cfg['suffixes'] as $suf) {
                $ik = $target . $suf;
                if (isset($ind[$ik])) {
                    $nilai = rand(80, 95);
                    $skor[] = $nilai;
                    $rincian[$ik] = ['skor' => $nilai];
                }
            }
            if (empty($skor)) continue;

            $rata = round(array_sum($skor) / count($skor), 2);
            $predikat = $rata >= 91 ? 'ISTIMEWA' : ($rata >= 76 ? 'BAIK' : ($rata >= 61 ? 'CUKUP' : ($rata >= 51 ? 'KURANG' : 'BURUK')));

            $this->db->table('penilaian_skp')->insert([
                'skp_id' => $skp[$target],
                'nilai_total' => $rata,
                'predikat' => $predikat,
                'catatan_penilai' => 'Kinerja baik, pertahankan.',
                'tanggal_penilaian' => '2026-05-30 10:00:00',
                'penilai_id' => $uid[$cfg['penilai']],
                'status_penilaian' => 'selesai',
                'rincian_nilai' => json_encode($rincian)
            ]);
            $penilaianCount++;

            $this->db->table('skp_master')->where('id', $skp[$target])->update(['status' => 'selesai']);
        }

        echo "Penilaian: $penilaianCount records.\n";

        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        //  SUMMARY
        // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        echo "\n====================================\n";
        echo "Data dummy berhasil dibuat!\n";
        echo "====================================\n";
        echo "Login credentials (password: 123):\n";
        echo "  admin           - Super Admin\n";
        echo "  rektor          - Rektor (SKP auto-approved)\n";
        echo "  admin_perencana - Admin Perencana\n";
        echo "  dekan_tarbiyah  - Dekan Tarbiyah (approved + dinilai)\n";
        echo "  dekan_dakwah    - Dekan Dakwah (approved + realisasi)\n";
        echo "  dekan_syariah   - Dekan Syariah (pengajuan)\n";
        echo "  dekan_ushuluddin- Dekan Ushuluddin (draft)\n";
        echo "  dekan_ekonomi   - Dekan Ekonomi (draft)\n";
        echo "  dekan_saintek   - Dekan SAINTEK (approved + realisasi)\n";
        echo "  kaprodi_tarbiyah- Kaprodi Tarbiyah (approved + dinilai)\n";
        echo "  kaprodi_dakwah  - Kaprodi Dakwah (approved + realisasi)\n";
        echo "  kaprodi_syariah - Kaprodi Syariah (pengajuan)\n";
        echo "  kaprodi_ushul...- Kaprodi Ushuluddin (draft)\n";
        echo "  kaprodi_ekonomi - Kaprodi Ekonomi (draft)\n";
        echo "  kaprodi_saintek - Kaprodi SAINTEK (approved + realisasi)\n";
        echo "  dosen_tarbiyah  - Dosen Tarbiyah (approved + dinilai)\n";
        echo "  dosen_dakwah    - Dosen Dakwah (approved + realisasi)\n";
        echo "  dosen_saintek   - Dosen SAINTEK (approved + realisasi)\n";
        echo "  dosen_syariah   - (belum buat SKP)\n";
        echo "  dosen_ushuluddin- (belum buat SKP)\n";
        echo "  dosen_ekonomi   - (belum buat SKP)\n";
        echo "====================================\n";
        echo "Total: 21 users, 1 periode, 16 SKP, " . $realCount . " realisasi, $approvalCount approval, $penilaianCount penilaian\n";
    }

    private function namaUser($u)
    {
        $map = [
            'admin' => 'Super Administrator',
            'admin_perencana' => 'Admin Perencana',
            'rektor' => 'Prof. Dr. H. Imam Sutomo, M.Ag.',
            'dekan_tarbiyah' => 'Dr. H. Suwito, M.Pd.',
            'kaprodi_tarbiyah' => 'Dr. Siti Fatimah, S.Pd., M.Pd.',
            'dosen_tarbiyah' => 'Ahmad Saifuddin, S.Pd., M.Pd.',
            'dekan_syariah' => 'Dr. H. Ali Imron, S.H., M.H.',
            'kaprodi_syariah' => 'Dr. Abdul Malik, Lc., M.H.',
            'dosen_syariah' => 'Nurul Hidayah, S.H., M.H.',
            'dekan_ushuluddin' => 'Dr. H. Ahmad Muzakki, M.Ag.',
            'kaprodi_ushuluddin' => 'Dr. KH. M. Sholeh, M.Ag.',
            'dosen_ushuluddin' => 'Fitria Laili, S.Ag., M.Ag.',
            'dekan_ekonomi' => 'Dr. H. M. Fauzan, S.E., M.Si.',
            'kaprodi_ekonomi' => 'Dr. Nur Kholis, S.E., M.Si.',
            'dosen_ekonomi' => 'Indah Permatasari, S.E., M.E.',
            'dekan_dakwah' => 'Dr. H. Agus Riyadi, M.Si.',
            'kaprodi_dakwah' => 'Dr. Fajar Rizqi, S.Sos., M.I.Kom.',
            'dosen_dakwah' => 'Rizky Amalia, S.Sos., M.Sos.',
            'dekan_saintek' => 'Dr. H. Nur Hadi, S.Si., M.Si.',
            'kaprodi_saintek' => 'Dr. Rina Wulandari, S.Kom., M.Kom.',
            'dosen_saintek' => 'Rizky Pratama, S.T., M.T.',
        ];
        return $map[$u] ?? ucfirst(str_replace('_', ' ', $u));
    }
}
