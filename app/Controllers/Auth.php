<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        // Check if lockout expiry is active
        $lockout = 0;
        $expiry = session()->get('lockout_expiry');

        if ($expiry && time() < $expiry) {
            $lockout = $expiry - time();
        } else {
            session()->remove('lockout_expiry');
            $this->clearFailedAttempts();
        }

        return view('auth/login', ['lockout' => $lockout]);  // FIXED: use auth/login
    }

    public function auth()
    {
        $session = session();
        $model = new UserModel();
        $db = \Config\Database::connect();

        $email = filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL);
        $password = trim($this->request->getPost('password'));
        $ip = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent();

        $maxAttempts = 5;
        $lockoutTime = 3 * 60;
        $timeWindow = date('Y-m-d H:i:s', strtotime('-15 minutes'));

        $builder = $db->table('login_attempts');
        $attempts = $builder->where('ip_address', $ip)->where('attempt_time >=', $timeWindow)->countAllResults();

        if ($attempts >= $maxAttempts) {
            $lastAttempt = $builder->selectMax('attempt_time')->where('ip_address', $ip)->get()->getRow();
            $lastTime = strtotime($lastAttempt->attempt_time);
            $lockoutExpiry = $lastTime + $lockoutTime;
            $remaining = $lockoutExpiry - time();

            if ($remaining > 0) {
                session()->set('lockout_expiry', $lockoutExpiry);
                return redirect()->to('/login')->with('error', 'Too many attempts. Try again later.');
            }
        }

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Clear failed attempts
            $builder->where('ip_address', $ip)->delete();

            $session->regenerate();
            $session->set([
                'user_id'   => $user['id'],
                'email'     => $user['email'],
                'name'      => $user['name'],
                'role'      => $user['role'],
                'logged_in' => true,
                'last_activity' => time()
            ]);

            $logModel = new LogModel();
            $logModel->addLog('Login: ' . $user['name'], 'LOGIN');

            return redirect()->to('/dashboard');
        } else {
            // Log the failed attempt
            $builder->insert([
                'email' => $email,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'attempt_time' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to('/login')->with('error', 'Invalid email or password');
        }
    }

    public function logout()
    {
        $logModel = new LogModel();
        $logModel->addLog('Logout', 'LOGOUT');

        session()->destroy();
        return redirect()->to('/login');
    }

    private function clearFailedAttempts()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('login_attempts');
        $ip = $this->request->getIPAddress();
        $timeThreshold = date('Y-m-d H:i:s', strtotime('-1 minute'));

        $builder->where('ip_address', $ip)->where('attempt_time <', $timeThreshold)->delete();
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    public function doRegister()
    {
        $model = new UserModel();

        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => 'staff',
            'status'   => 'active'
        ]);

        $logModel = new LogModel();
        $logModel->addLog('New user registered: ' . $this->request->getPost('email'), 'REGISTRATION');

        return redirect()->to('/login')->with('message', 'Registration successful! Please login.');
    }
}