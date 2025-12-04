<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\Hash;

class AuthController extends BaseController
{
    protected $userModel;
    protected $validation;
    protected $session;
    protected $email;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->email = \Config\Services::email();
    }

    /**
     * Menampilkan halaman login
     */
    public function login()
    {
        // Jika user sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - CENDRATAMA',
            'validation' => $this->validation
        ];

        return view('auth/login', $data);
    }

    /**
     * Proses login
     */
    public function processLogin()
    {
        // Validasi input
        $rules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'valid_email' => '{field} tidak valid'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Cari user berdasarkan email
        $user = $this->userModel->where('email', $email)
            ->where('status', 'active')
            ->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email atau password salah');
            return redirect()->to('/login')->withInput();
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Email atau password salah');
            return redirect()->to('/login')->withInput();
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['id'],
            'user_name' => $user['full_name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'user_avatar' => $user['avatar'],
            'isLoggedIn' => true
        ];

        session()->set($sessionData);

        // Remember me functionality
        if ($remember) {
            $this->setRememberMeToken($user['id']);
        }

        // Update last login
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Redirect berdasarkan role
        if ($user['role'] == 'admin') {
            return redirect()->to('/admin');
        } else {
            return redirect()->to('/dashboard');
        }
    }

    /**
     * Menampilkan halaman register
     */
    public function register()
    {
        // Jika user sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Daftar - CENDRATAMA',
            'validation' => $this->validation
        ];

        return view('auth/register', $data);
    }

    /**
     * Proses registrasi
     */
    public function processRegister()
    {
        // Validasi input
        $rules = [
            'full_name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter',
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'valid_email' => '{field} tidak valid',
                    'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'phone' => [
                'label' => 'Nomor Telepon',
                'rules' => 'required|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter',
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter'
                ]
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'matches' => 'Password tidak cocok'
                ]
            ],
            'terms' => [
                'label' => 'Syarat & Ketentuan',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Anda harus menyetujui {field}'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate verification token
        $verificationToken = bin2hex(random_bytes(32));

        // Data untuk disimpan
        $userData = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'verification_token' => $verificationToken,
            'role' => 'user',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Simpan user
        if ($this->userModel->insert($userData)) {
            $userId = $this->userModel->getInsertID();
            
            // Kirim email verifikasi
            $this->sendVerificationEmail($userData['email'], $userData['full_name'], $verificationToken);
            
            // Set session flash data
            session()->setFlashdata('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
            
            // Log aktivitas
            $this->logActivity($userId, 'register', 'User melakukan registrasi');
            
            return redirect()->to('/login');
        } else {
            session()->setFlashdata('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
            return redirect()->to('/register')->withInput();
        }
    }

    /**
     * Verifikasi email
     */
    public function verify($token)
    {
        // Cari user berdasarkan token
        $user = $this->userModel->where('verification_token', $token)
            ->where('status', 'pending')
            ->first();

        if ($user) {
            // Update status user
            $this->userModel->update($user['id'], [
                'status' => 'active',
                'verified_at' => date('Y-m-d H:i:s'),
                'verification_token' => null
            ]);

            session()->setFlashdata('success', 'Akun Anda telah berhasil diverifikasi! Silakan login.');
            
            // Log aktivitas
            $this->logActivity($user['id'], 'verify_email', 'User melakukan verifikasi email');
            
            return redirect()->to('/login');
        } else {
            session()->setFlashdata('error', 'Token verifikasi tidak valid atau sudah kedaluwarsa.');
            return redirect()->to('/login');
        }
    }

    /**
     * Menampilkan halaman lupa password
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Lupa Password - CENDRATAMA',
            'validation' => $this->validation
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Proses lupa password
     */
    public function processForgotPassword()
    {
        $rules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'valid_email' => '{field} tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        
        // Cari user berdasarkan email
        $user = $this->userModel->where('email', $email)
            ->where('status', 'active')
            ->first();

        if ($user) {
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Update user dengan reset token
            $this->userModel->update($user['id'], [
                'reset_token' => $resetToken,
                'reset_expires' => $expiresAt
            ]);

            // Kirim email reset password
            $this->sendResetPasswordEmail($user['email'], $user['full_name'], $resetToken);

            session()->setFlashdata('success', 'Instruksi reset password telah dikirim ke email Anda.');
            
            // Log aktivitas
            $this->logActivity($user['id'], 'request_password_reset', 'User meminta reset password');
        }

        // Selalu tampilkan pesan sukses untuk keamanan
        session()->setFlashdata('success', 'Jika email terdaftar, instruksi reset password telah dikirim.');
        return redirect()->to('/login');
    }

    /**
     * Menampilkan halaman reset password
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            session()->setFlashdata('error', 'Token reset password tidak valid.');
            return redirect()->to('/login');
        }

        // Cek validitas token
        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->where('status', 'active')
            ->first();

        if (!$user) {
            session()->setFlashdata('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Reset Password - CENDRATAMA',
            'token' => $token,
            'validation' => $this->validation
        ];

        return view('auth/reset_password', $data);
    }
     public function verificationRequired()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if ($user['status'] === 'active' && $user['verified_at']) {
            // Redirect to intended URL or dashboard
            $intendedUrl = session()->get('intended_url') ?? '/dashboard';
            session()->remove('intended_url');
            return redirect()->to($intendedUrl);
        }

        $data = [
            'title' => 'Verifikasi Email Diperlukan - CENDRATAMA',
            'user' => $user
        ];

        return view('auth/verification_required', $data);
    }

    /**
     * Proses reset password
     */
    public function processResetPassword()
    {
        $token = $this->request->getPost('token');

        $rules = [
            'token' => 'required',
            'password' => [
                'label' => 'Password Baru',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter'
                ]
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'matches' => 'Password tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Cek validitas token
        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->where('status', 'active')
            ->first();

        if (!$user) {
            session()->setFlashdata('error', 'Token reset password tidak valid atau sudah kedaluwarsa.');
            return redirect()->to('/login');
        }

        // Update password user
        $newPassword = $this->request->getPost('password');
        
        $this->userModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_expires' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Kirim email notifikasi
        $this->sendPasswordChangedEmail($user['email'], $user['full_name']);

        session()->setFlashdata('success', 'Password berhasil direset! Silakan login dengan password baru.');
        
        // Log aktivitas
        $this->logActivity($user['id'], 'reset_password', 'User melakukan reset password');

        return redirect()->to('/login');
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Log aktivitas sebelum logout
        if (session()->get('isLoggedIn')) {
            $this->logActivity(session()->get('user_id'), 'logout', 'User melakukan logout');
        }

        // Hapus remember me cookie
        $this->clearRememberMeToken();

        // Hancurkan session
        session()->destroy();

        return redirect()->to('/login');
    }

    /**
     * Kirim email verifikasi
     */
    private function sendVerificationEmail($email, $name, $token)
    {
        $verificationLink = base_url("verify/{$token}");
        
        $message = view('emails/verification', [
            'name' => $name,
            'verification_link' => $verificationLink
        ]);

        $this->email->setTo($email);
        $this->email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
        $this->email->setSubject('Verifikasi Akun CENDRATAMA');
        $this->email->setMessage($message);

        return $this->email->send();
    }

    /**
     * Kirim email reset password
     */
    private function sendResetPasswordEmail($email, $name, $token)
    {
        $resetLink = base_url("reset-password/{$token}");
        
        $message = view('emails/reset_password', [
            'name' => $name,
            'reset_link' => $resetLink
        ]);

        $this->email->setTo($email);
        $this->email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
        $this->email->setSubject('Reset Password - CENDRATAMA');
        $this->email->setMessage($message);

        return $this->email->send();
    }

    /**
     * Kirim email password berhasil diubah
     */
    private function sendPasswordChangedEmail($email, $name)
    {
        $message = view('emails/password_changed', [
            'name' => $name
        ]);

        $this->email->setTo($email);
        $this->email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
        $this->email->setSubject('Password Berhasil Diubah - CENDRATAMA');
        $this->email->setMessage($message);

        return $this->email->send();
    }

    /**
     * Set remember me token
     */
    private function setRememberMeToken($userId)
    {
        $rememberToken = bin2hex(random_bytes(32));
        
        // Simpan token di database
        $this->userModel->update($userId, [
            'remember_token' => $rememberToken
        ]);

        // Set cookie (30 hari)
        helper('cookie');
        set_cookie('remember_me', $rememberToken, 30 * 24 * 60 * 60);
    }

    /**
     * Clear remember me token
     */
    private function clearRememberMeToken()
    {
        if (session()->get('isLoggedIn')) {
            $userId = session()->get('user_id');
            $this->userModel->update($userId, [
                'remember_token' => null
            ]);
        }

        // Hapus cookie
        helper('cookie');
        delete_cookie('remember_me');
    }

    /**
     * Auto login dengan remember me token
     */
    public function autoLoginWithToken()
    {
        helper('cookie');
        $rememberToken = get_cookie('remember_me');

        if ($rememberToken && !session()->get('isLoggedIn')) {
            $user = $this->userModel->where('remember_token', $rememberToken)
                ->where('status', 'active')
                ->first();

            if ($user) {
                $sessionData = [
                    'user_id' => $user['id'],
                    'user_name' => $user['full_name'],
                    'user_email' => $user['email'],
                    'user_role' => $user['role'],
                    'user_avatar' => $user['avatar'],
                    'isLoggedIn' => true
                ];

                session()->set($sessionData);
                return true;
            }
        }

        return false;
    }

    /**
     * Log aktivitas user
     */
    private function logActivity($userId, $activity, $description)
    {
        $activityModel = new \App\Models\ActivityLogModel();
        
        $activityData = [
            'user_id' => $userId,
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $activityModel->insert($activityData);
    }

    /**
     * Profile user
     */
    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $data = [
            'title' => 'Profil Saya - CENDRATAMA',
            'user' => $user,
            'validation' => $this->validation
        ];

        return view('auth/profile', $data);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $rules = [
            'full_name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter',
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'phone' => [
                'label' => 'Nomor Telepon',
                'rules' => 'required|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter',
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'address' => [
                'label' => 'Alamat',
                'rules' => 'max_length[255]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ]
        ];

        // Jika email diubah, cek keunikan
        if ($this->request->getPost('email') != $user['email']) {
            $rules['email'] = [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'valid_email' => '{field} tidak valid',
                    'is_unique' => '{field} sudah terdaftar'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Handle avatar upload
        $avatar = $user['avatar'];
        $avatarFile = $this->request->getFile('avatar');

        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $newName = $avatarFile->getRandomName();
            $avatarFile->move(ROOTPATH . 'public/uploads/avatars', $newName);
            
            // Hapus avatar lama jika bukan default
            if ($avatar && $avatar != 'default.png' && file_exists(ROOTPATH . 'public/uploads/avatars/' . $avatar)) {
                unlink(ROOTPATH . 'public/uploads/avatars/' . $avatar);
            }
            
            $avatar = $newName;
        }

        // Data untuk diupdate
        $updateData = [
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'avatar' => $avatar,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update email jika diubah
        if ($this->request->getPost('email') != $user['email']) {
            $updateData['email'] = $this->request->getPost('email');
            $updateData['email_verified_at'] = null;
            $updateData['status'] = 'pending';
            
            // Generate verification token baru
            $verificationToken = bin2hex(random_bytes(32));
            $updateData['verification_token'] = $verificationToken;
            
            // Kirim email verifikasi baru
            $this->sendVerificationEmail(
                $this->request->getPost('email'),
                $this->request->getPost('full_name'),
                $verificationToken
            );
        }

        // Update profile
        if ($this->userModel->update($userId, $updateData)) {
            // Update session jika nama berubah
            if ($updateData['full_name'] != $user['full_name']) {
                session()->set('user_name', $updateData['full_name']);
            }
            
            // Update session jika avatar berubah
            if ($avatar != $user['avatar']) {
                session()->set('user_avatar', $avatar);
            }
            
            session()->setFlashdata('success', 'Profil berhasil diperbarui!');
            
            // Log aktivitas
            $this->logActivity($userId, 'update_profile', 'User memperbarui profil');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }

        return redirect()->to('/profile');
    }

    /**
     * Ganti password
     */
    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        $rules = [
            'current_password' => [
                'label' => 'Password Saat Ini',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi'
                ]
            ],
            'new_password' => [
                'label' => 'Password Baru',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter'
                ]
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'matches' => 'Password tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Verifikasi password saat ini
        $currentPassword = $this->request->getPost('current_password');
        
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password saat ini salah.');
            return redirect()->back()->withInput();
        }

        // Update password
        $newPassword = $this->request->getPost('new_password');
        
        $this->userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Kirim email notifikasi
        $this->sendPasswordChangedEmail($user['email'], $user['full_name']);

        session()->setFlashdata('success', 'Password berhasil diubah!');
        
        // Log aktivitas
        $this->logActivity($userId, 'change_password', 'User mengganti password');

        return redirect()->to('/profile');
    }

    /**
     * Resend verification email
     */
    public function resendVerification()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if ($user['status'] == 'active') {
            session()->setFlashdata('error', 'Akun Anda sudah terverifikasi.');
            return redirect()->to('/dashboard');
        }

        // Generate token baru
        $verificationToken = bin2hex(random_bytes(32));
        
        $this->userModel->update($userId, [
            'verification_token' => $verificationToken
        ]);

        // Kirim email verifikasi
        $this->sendVerificationEmail($user['email'], $user['full_name'], $verificationToken);

        session()->setFlashdata('success', 'Email verifikasi telah dikirim ulang. Silakan cek email Anda.');
        
        // Log aktivitas
        $this->logActivity($userId, 'resend_verification', 'User meminta pengiriman ulang email verifikasi');

        return redirect()->back();
    }
}