<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataDummySeeder extends Seeder
{
    public function run()
    {
        // Cek apakah sudah ada users
        $existing = $this->db->table('users')->countAll();
        if ($existing > 0) {
            echo "Data sudah ada, skip seeder.\n";
            return;
        }

        // 1. Buat Periode
        $this->db->table('periode')->insert([
            'tahun' => 2026,
            'nama_periode' => 'Tahun 2026',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'batas_akhir_pengajuan_skp' => '2026-01-31',
            'batas_akhir_realisasi' => '2026-12-31',
            'batas_akhir_penilaian' => '2027-01-15',
            'is_active' => 1
        ]);
        echo "Periode created.\n";

        // 2. Buat Users (hierarki: super_admin, rektor, dekan, kaprodi, dosen)
        $password = password_hash('password123', PASSWORD_DEFAULT);

        $users = [
            [
                'username' => 'admin',
                'password' => $password,
                'nama_lengkap' => 'Super Admin',
                'nip' => '000000000000000001',
                'email' => 'admin@uinsalatiga.ac.id',
                'unit_kerja' => 'UIN Salatiga',
                'jabatan' => 'Administrator Sistem',
                'role' => 'super_admin',
                'atasan_id' => null,
                'status' => 'aktif'
            ],
            [
                'username' => 'rektor',
                'password' => $password,
                'nama_lengkap' => 'Prof. Dr. Rektor UIN Salatiga',
                'nip' => '198001012005011001',
                'email' => 'rektor@uinsalatiga.ac.id',
                'unit_kerja' => 'UIN Salatiga',
                'jabatan' => 'Rektor',
                'role' => 'rektor',
                'atasan_id' => null,
                'status' => 'aktif'
            ],
            [
                'username' => 'dekan',
                'password' => $password,
                'nama_lengkap' => 'Dr. Dekan FTIK',
                'nip' => '198101012005011002',
                'email' => 'dekan@uinsalatiga.ac.id',
                'unit_kerja' => 'FTIK',
                'jabatan' => 'Dekan Fakultas',
                'role' => 'dekan',
                'atasan_id' => null, // akan diupdate setelah insert
                'status' => 'aktif'
            ],
            [
                'username' => 'kaprodi',
                'password' => $password,
                'nama_lengkap' => 'Ketua Program Studi',
                'nip' => '198201012005011003',
                'email' => 'kaprodi@uinsalatiga.ac.id',
                'unit_kerja' => 'FTIK',
                'jabatan' => 'Kaprodi',
                'role' => 'kaprodi',
                'atasan_id' => null,
                'status' => 'aktif'
            ],
            [
                'username' => 'dosen',
                'password' => $password,
                'nama_lengkap' => 'Dosen Tetap',
                'nip' => '198301012005011004',
                'email' => 'dosen@uinsalatiga.ac.id',
                'unit_kerja' => 'FTIK',
                'jabatan' => 'Dosen',
                'role' => 'dosen',
                'atasan_id' => null,
                'status' => 'aktif'
            ],
            [
                'username' => 'admin_perencana',
                'password' => $password,
                'nama_lengkap' => 'Admin Perencana',
                'nip' => '198401012005011005',
                'email' => 'admin_perencana@uinsalatiga.ac.id',
                'unit_kerja' => 'BIRO',
                'jabatan' => 'Staff Perencanaan',
                'role' => 'admin_perencana',
                'atasan_id' => null,
                'status' => 'aktif'
            ]
        ];

        foreach ($users as $user) {
            $this->db->table('users')->insert($user);
        }

        // Update atasan_id based on hierarchy
        $rektorId = 2;
        $dekanId = 3;
        $kaprodiId = 4;

        $this->db->table('users')->where('id', $dekanId)->update(['atasan_id' => $rektorId]);
        $this->db->table('users')->where('id', $kaprodiId)->update(['atasan_id' => $dekanId]);
        $this->db->table('users')->where('username', 'dosen')->update(['atasan_id' => $kaprodiId]);

        echo "Users created with hierarchy:\n";
        echo "  Super Admin (admin)\n";
        echo "  Admin Perencana (admin_perencana)\n";
        echo "  Rektor -> Dekan -> Kaprodi -> Dosen\n";

        // 3. Buat SKP untuk Rektor (auto-approved)
        $this->db->table('skp_master')->insert([
            'user_id' => $rektorId,
            'periode_id' => 1,
            'pendekatan' => 'kuantitatif',
            'status' => 'disetujui',
            'catatan_atasan' => 'Disetujui otomatis (Rektor)',
            'tanggal_pengajuan' => '2026-01-15 08:00:00',
            'tanggal_approval' => '2026-01-15 08:00:01'
        ]);
        $rektorSkpId = $this->db->insertID();

        // RHK Rektor
        $this->db->table('rhk')->insert([
            'skp_id' => $rektorSkpId,
            'nama_rhk' => 'Menyusun Renstra Universitas 2026-2030',
            'jenis_rhk' => 'kuantitatif',
            'klasifikasi' => 'utama',
            'target_kuantitas' => 1,
            'target_satuan' => 'dokumen',
            'target_kualitas' => 'Dokumen Renstra disahkan Senat Universitas',
            'target_waktu' => '2026-06-30',
            'bobot' => 50
        ]);
        $rhk1Id = $this->db->insertID();

        $this->db->table('rhk')->insert([
            'skp_id' => $rektorSkpId,
            'nama_rhk' => 'Meningkatkan Peringkat Akreditasi Universitas',
            'jenis_rhk' => 'kuantitatif',
            'klasifikasi' => 'utama',
            'target_kuantitas' => 1,
            'target_satuan' => 'predikat',
            'target_kualitas' => 'Akreditasi Unggul',
            'target_waktu' => '2026-12-31',
            'bobot' => 50
        ]);
        $rhk2Id = $this->db->insertID();

        // Indikator RHK Rektor
        $this->db->table('rhk_indikator')->insert([
            'rhk_id' => $rhk1Id,
            'indikator' => 'Renstra disusun tepat waktu',
            'target' => 'Juni 2026',
            'perspektif' => 'Proses'
        ]);
        $this->db->table('rhk_indikator')->insert([
            'rhk_id' => $rhk2Id,
            'indikator' => 'Nilai Akreditasi meningkat',
            'target' => 'Predikat Unggul',
            'perspektif' => 'Hasil'
        ]);

        // 4. SKP Dekan (menunggu approval)
        $this->db->table('skp_master')->insert([
            'user_id' => $dekanId,
            'periode_id' => 1,
            'pendekatan' => 'kuantitatif',
            'status' => 'draft',
            'created_at' => '2026-01-16 09:00:00'
        ]);
        $dekanSkpId = $this->db->insertID();

        // RHK Dekan (intervensi dari RHK Rektor)
        $this->db->table('rhk')->insert([
            'skp_id' => $dekanSkpId,
            'intervensi_dari_type' => 'rhk_atasan',
            'intervensi_dari_id' => $rhk2Id,
            'intervensi_dari_nama' => 'Meningkatkan Peringkat Akreditasi Universitas',
            'nama_rhk' => 'Menyusun Dokumen Akreditasi Program Studi',
            'jenis_rhk' => 'kuantitatif',
            'klasifikasi' => 'utama',
            'target_kuantitas' => 1,
            'target_satuan' => 'dokumen',
            'target_kualitas' => 'Dokumen akreditasi lengkap dan sesuai standar',
            'target_waktu' => '2026-08-31',
            'bobot' => 60
        ]);
        $dekanRhk1Id = $this->db->insertID();

        $this->db->table('rhk')->insert([
            'skp_id' => $dekanSkpId,
            'intervensi_dari_type' => 'rhk_atasan',
            'intervensi_dari_id' => $rhk1Id,
            'intervensi_dari_nama' => 'Menyusun Renstra Universitas 2026-2030',
            'nama_rhk' => 'Menyusun Renstra Fakultas 2026-2030',
            'jenis_rhk' => 'kuantitatif',
            'klasifikasi' => 'utama',
            'target_kuantitas' => 1,
            'target_satuan' => 'dokumen',
            'target_kualitas' => 'Renstra Fakultas selaras dengan Renstra Universitas',
            'target_waktu' => '2026-07-31',
            'bobot' => 40
        ]);
        $dekanRhk2Id = $this->db->insertID();

        // 5. SKP Kaprodi (draft)
        $this->db->table('skp_master')->insert([
            'user_id' => $kaprodiId,
            'periode_id' => 1,
            'pendekatan' => 'kuantitatif',
            'status' => 'draft',
            'created_at' => '2026-01-17 10:00:00'
        ]);
        $kaprodiSkpId = $this->db->insertID();

        echo "\nData dummy berhasil dibuat!\n";
        echo "====================================\n";
        echo "Login credentials (password: password123):\n";
        echo "  admin       - Super Admin\n";
        echo "  rektor      - Rektor (otomatis disetujui)\n";
        echo "  dekan       - Dekan (draft, bawahan Rektor)\n";
        echo "  kaprodi     - Kaprodi (draft, bawahan Dekan)\n";
        echo "  dosen       - Dosen (belum buat SKP, bawahan Kaprodi)\n";
        echo "  admin_perencana - Admin Perencana\n";
        echo "====================================\n";
    }
}
