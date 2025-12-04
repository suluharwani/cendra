<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            $userRole = session()->get('user_role');
            
            // Redirect based on user role
            switch ($userRole) {
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                    break;
                case 'user':
                default:
                    return redirect()->to('/dashboard');
                    break;
            }
        }
        
        // Optional: Check for specific guest-only page requirements
        if ($arguments) {
            foreach ($arguments as $argument) {
                switch ($argument) {
                    case 'verified_email':
                        // You can add special checks here
                        // For example, check if user needs to verify email
                        break;
                        
                    case 'no_verification':
                        // Pages that don't require verification
                        break;
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here after response is sent
    }
}