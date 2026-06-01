<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserManagement extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();
        
        // Get atasan name for each user
        foreach ($users as &$user) {
            if ($user['atasan_id']) {
                $atasan = $userModel->find($user['atasan_id']);
                $user['atasan_nama'] = $atasan['nama_lengkap'] ?? '-';
            } else {
                $user['atasan_nama'] = '-';
            }
        }
        
        $data = [
            'title' => 'Manajemen User',
            'users' => $users
        ];
        
        return view('user/index', $data);
    }

    public function create()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();
        
        $data = [
            'title' => 'Tambah User Baru',
            'users' => $users
        ];
        
        return view('user/create', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'nama_lengkap' => 'required',
            'unit_kerja' => 'required',
            'jabatan' => 'required',
            'role' => 'required',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userModel = new UserModel();
        $userModel->insert([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'nip' => $this->request->getPost('nip'),
            'email' => $this->request->getPost('email'),
            'unit_kerja' => $this->request->getPost('unit_kerja'),
            'jabatan' => $this->request->getPost('jabatan'),
            'role' => $this->request->getPost('role'),
            'atasan_id' => $this->request->getPost('atasan_id') ?: null,
            'status' => $this->request->getPost('status')
        ]);
        
        return redirect()->to('/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        $users = $userModel->findAll();
        
        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'users' => $users
        ];
        
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }
        
        $rules = [
            'nama_lengkap' => 'required',
            'unit_kerja' => 'required',
            'jabatan' => 'required',
            'role' => 'required'
        ];
        
        if ($this->request->getPost('username') != $user['username']) {
            $rules['username'] = 'required|is_unique[users.username]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $updateData = [
            'username' => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'nip' => $this->request->getPost('nip'),
            'email' => $this->request->getPost('email'),
            'unit_kerja' => $this->request->getPost('unit_kerja'),
            'jabatan' => $this->request->getPost('jabatan'),
            'role' => $this->request->getPost('role'),
            'atasan_id' => $this->request->getPost('atasan_id') ?: null,
            'status' => $this->request->getPost('status')
        ];
        
        // Update password if provided
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        
        $userModel->update($id, $updateData);
        
        return redirect()->to('/user')->with('success', 'User berhasil diupdate');
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        
        // Check if user has subordinates
        $bawahan = $userModel->where('atasan_id', $id)->countAllResults();
        if ($bawahan > 0) {
            return redirect()->back()->with('error', 'User memiliki bawahan, tidak dapat dihapus');
        }
        
        $userModel->delete($id);
        return redirect()->to('/user')->with('success', 'User berhasil dihapus');
    }

    public function resetPassword($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/user')->with('error', 'User tidak ditemukan');
        }
        
        $defaultPassword = 'password123';
        $userModel->update($id, [
            'password' => password_hash($defaultPassword, PASSWORD_DEFAULT)
        ]);
        
        return redirect()->to('/user')->with('success', 'Password berhasil direset menjadi: ' . $defaultPassword);
    }
}