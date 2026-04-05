<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogModel;

class Users extends BaseController
{
    public function __construct()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Admin access only');
        }
    }

    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->findAll();
        return view('users/index', $data);
    }

    public function store()
    {
        $model = new UserModel();
        $model->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'phone'    => $this->request->getPost('phone'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
            'status'   => $this->request->getPost('status')
        ]);
        $logModel = new LogModel();
        $logModel->addLog('Added user: ' . $this->request->getPost('email'), 'USER');
        return redirect()->to('/users')->with('message', 'User added');
    }

    public function update($id)
    {
        $model = new UserModel();
        $data = [
            'name'   => $this->request->getPost('name'),
            'email'  => $this->request->getPost('email'),
            'phone'  => $this->request->getPost('phone'),
            'role'   => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }
        $model->update($id, $data);
        $logModel = new LogModel();
        $logModel->addLog('Updated user ID: ' . $id, 'USER');
        return redirect()->to('/users')->with('message', 'User updated');
    }

    public function delete($id)
    {
        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }
        $model = new UserModel();
        $model->delete($id);
        $logModel = new LogModel();
        $logModel->addLog('Deleted user ID: ' . $id, 'USER');
        return redirect()->to('/users')->with('message', 'User deleted');
    }
}