<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $userId = session()->get('id');
        $data['user'] = $model->find($userId);
        return view('profile/index', $data);
    }

    public function update()
    {
        $model = new UserModel();
        $userId = session()->get('id');

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'nip'          => $this->request->getPost('nip'),
            'email'        => $this->request->getPost('email'),
            'jabatan'      => $this->request->getPost('jabatan'),
        ];

        $model->update($userId, $data);

        session()->set('nama_lengkap', $data['nama_lengkap']);

        return redirect()->to('/profil')->with('success', 'Profil berhasil diupdate.');
    }

    public function changePassword()
    {
        $model = new UserModel();
        $userId = session()->get('id');

        $rules = [
            'password_lama' => 'required',
            'password_baru' => 'required|min_length[6]',
            'konfirmasi'    => 'required|matches[password_baru]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal. Password baru min 6 karakter.');
        }

        $user = $model->find($userId);

        if (!password_verify($this->request->getPost('password_lama'), $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        $model->update($userId, [
            'password' => password_hash($this->request->getPost('password_baru'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/profil')->with('success', 'Password berhasil diubah.');
    }
}
