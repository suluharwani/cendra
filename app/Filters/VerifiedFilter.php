<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VerifiedFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // First check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu');
            return redirect()->to('/login')->with('redirect', current_url());
        }
        
        // Check if user is verified (email verification)
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('user_id'));
        
        if (!$user || $user['status'] !== 'active' || !$user['verified_at']) {
            session()->setFlashdata('error', 'Akun Anda belum diverifikasi. Silakan verifikasi email terlebih dahulu.');
            
            // Store intended URL for redirect after verification
            session()->set('intended_url', current_url());
            
            return redirect()->to('/verification-required');
        }
        
        // Check for additional verification requirements
        if ($arguments) {
            foreach ($arguments as $argument) {
                switch ($argument) {
                    case 'phone':
                        if (empty($user['phone_verified_at'])) {
                            session()->setFlashdata('error', 'Nomor telepon belum diverifikasi.');
                            return redirect()->to('/verify-phone');
                        }
                        break;
                        
                    case 'kyc':
                        if (!$user['kyc_verified']) {
                            session()->setFlashdata('error', 'Verifikasi KYC diperlukan untuk akses ini.');
                            return redirect()->to('/kyc-verification');
                        }
                        break;
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}