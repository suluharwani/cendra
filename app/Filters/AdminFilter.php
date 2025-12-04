<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu');
            return redirect()->to('/login');
        }
        
        // Check if user is admin
        if (session()->get('user_role') != 'admin') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}