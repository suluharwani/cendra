<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\ServiceModel;
use App\Models\ProductModel;
use App\Models\WishlistModel;
use App\Models\ConsultationModel;
use App\Models\InvoiceModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $orderModel;
    protected $serviceModel;
    protected $productModel;
    protected $wishlistModel;
    protected $consultationModel;
    protected $invoiceModel;
    protected $validation;

    public function __construct()
    {
        helper(['form', 'url', 'session', 'number', 'text']);
        
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->serviceModel = new ServiceModel();
        $this->productModel = new ProductModel();
        $this->wishlistModel = new WishlistModel();
        $this->consultationModel = new ConsultationModel();
        $this->invoiceModel = new InvoiceModel();
        $this->validation = \Config\Services::validation();
        
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    /**
     * Dashboard User
     */
    public function dashboard()
    {
        $userId = session()->get('user_id');
        
        // Get user statistics
        $stats = [
            'total_orders' => $this->orderModel->where('user_id', $userId)->countAllResults(),
            'pending_orders' => $this->orderModel->where('user_id', $userId)
                ->where('status', 'pending')
                ->countAllResults(),
            'completed_orders' => $this->orderModel->where('user_id', $userId)
                ->where('status', 'completed')
                ->countAllResults(),
            'total_wishlist' => $this->wishlistModel->where('user_id', $userId)->countAllResults(),
            'total_consultations' => $this->consultationModel->where('user_id', $userId)->countAllResults(),
            'total_spent' => $this->orderModel->selectSum('total_amount')
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->get()
                ->getRow()->total_amount ?? 0
        ];
        
        // Get recent orders
        $recentOrders = $this->orderModel->select('orders.*, services.title as service_name')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->where('orders.user_id', $userId)
            ->orderBy('orders.created_at', 'DESC')
            ->limit(5)
            ->find();
        
        // Get recent consultations
        $recentConsultations = $this->consultationModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();
        
        // Get wishlist items
        $wishlistItems = $this->wishlistModel->select('wishlist.*, products.title, products.price, products.image')
            ->join('products', 'products.id = wishlist.product_id')
            ->where('wishlist.user_id', $userId)
            ->orderBy('wishlist.created_at', 'DESC')
            ->limit(5)
            ->find();
        
        $data = [
            'title' => 'Dashboard - CENDRATAMA',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentConsultations' => $recentConsultations,
            'wishlistItems' => $wishlistItems,
            'user' => $this->userModel->find($userId)
        ];
        
        return view('user/dashboard', $data);
    }

    /**
     * Profile User
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Profil Saya - CENDRATAMA',
            'user' => $user,
            'validation' => $this->validation
        ];
        
        return view('user/profile', $data);
    }

    /**
     * Update Profile
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/dashboard');
        }
        
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
                'rules' => 'max_length[500]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'company' => [
                'label' => 'Perusahaan',
                'rules' => 'max_length[100]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ]
        ];
        
        // Jika email diubah
        $newEmail = $this->request->getPost('email');
        if ($newEmail && $newEmail != $user['email']) {
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
            // Validasi file
            $validationRule = [
                'avatar' => [
                    'label' => 'Foto Profil',
                    'rules' => 'uploaded[avatar]'
                        . '|is_image[avatar]'
                        . '|mime_in[avatar,image/jpg,image/jpeg,image/png]'
                        . '|max_size[avatar,2048]',
                    'errors' => [
                        'uploaded' => 'Pilih {field} terlebih dahulu',
                        'is_image' => '{field} harus berupa gambar',
                        'mime_in' => '{field} harus berformat JPG, JPEG, atau PNG',
                        'max_size' => 'Ukuran {field} maksimal 2MB'
                    ]
                ]
            ];
            
            if ($this->validate($validationRule)) {
                $newName = $avatarFile->getRandomName();
                $avatarFile->move(ROOTPATH . 'public/uploads/avatars', $newName);
                
                // Hapus avatar lama jika bukan default
                if ($avatar && $avatar != 'default.png' && file_exists(ROOTPATH . 'public/uploads/avatars/' . $avatar)) {
                    unlink(ROOTPATH . 'public/uploads/avatars/' . $avatar);
                }
                
                $avatar = $newName;
            }
        }
        
        // Prepare update data
        $updateData = [
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'company' => $this->request->getPost('company'),
            'avatar' => $avatar,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Jika email berubah
        $emailChanged = false;
        if ($newEmail && $newEmail != $user['email']) {
            $updateData['email'] = $newEmail;
            $updateData['email_verified_at'] = null;
            $updateData['status'] = 'pending';
            
            // Generate verification token baru
            $verificationToken = bin2hex(random_bytes(32));
            $updateData['verification_token'] = $verificationToken;
            
            $emailChanged = true;
        }
        
        // Update profile
        if ($this->userModel->update($userId, $updateData)) {
            // Update session data
            session()->set('user_name', $updateData['full_name']);
            if ($avatar != $user['avatar']) {
                session()->set('user_avatar', $avatar);
            }
            
            // Jika email berubah, kirim email verifikasi
            if ($emailChanged) {
                // Load email service
                $email = \Config\Services::email();
                
                $verificationLink = base_url("verify/{$verificationToken}");
                $message = view('emails/verification', [
                    'name' => $updateData['full_name'],
                    'verification_link' => $verificationLink
                ]);
                
                $email->setTo($newEmail);
                $email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
                $email->setSubject('Verifikasi Email Baru - CENDRATAMA');
                $email->setMessage($message);
                $email->send();
                
                session()->setFlashdata('success', 'Profil berhasil diperbarui! Silakan verifikasi email baru Anda.');
            } else {
                session()->setFlashdata('success', 'Profil berhasil diperbarui!');
            }
            
            // Log activity
            $this->logActivity($userId, 'update_profile', 'User memperbarui profil');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
        
        return redirect()->to('/profile');
    }

    /**
     * Change Password
     */
    public function changePassword()
    {
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
        
        // Verify current password
        $currentPassword = $this->request->getPost('current_password');
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password saat ini salah.');
            return redirect()->back()->withInput();
        }
        
        // Update password
        $newPassword = $this->request->getPost('new_password');
        
        if ($this->userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ])) {
            // Kirim email notifikasi
            $email = \Config\Services::email();
            
            $message = view('emails/password_changed', [
                'name' => $user['full_name']
            ]);
            
            $email->setTo($user['email']);
            $email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
            $email->setSubject('Password Berhasil Diubah - CENDRATAMA');
            $email->setMessage($message);
            $email->send();
            
            session()->setFlashdata('success', 'Password berhasil diubah!');
            
            // Log activity
            $this->logActivity($userId, 'change_password', 'User mengganti password');
        } else {
            session()->setFlashdata('error', 'Gagal mengubah password. Silakan coba lagi.');
        }
        
        return redirect()->to('/profile');
    }

    /**
     * Orders List
     */
    public function orders()
    {
        $userId = session()->get('user_id');
        
        // Get filter parameters
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 10;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->orderModel->select('orders.*, services.title as service_name, services.slug as service_slug')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->where('orders.user_id', $userId);
        
        // Apply filters
        if ($status && $status != 'all') {
            $builder->where('orders.status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('orders.order_number', $search)
                ->orLike('services.title', $search)
                ->groupEnd();
        }
        
        // Get total for pagination
        $total = $builder->countAllResults(false);
        
        // Get orders with pagination
        $orders = $builder->orderBy('orders.created_at', 'DESC')
            ->paginate($limit, 'default', $page);
        
        $pager = $this->orderModel->pager;
        
        $data = [
            'title' => 'Pesanan Saya - CENDRATAMA',
            'orders' => $orders,
            'pager' => $pager,
            'total' => $total,
            'status' => $status,
            'search' => $search,
            'limit' => $limit
        ];
        
        return view('user/orders', $data);
    }

    /**
     * Order Detail
     */
    public function orderDetail($orderId)
    {
        $userId = session()->get('user_id');
        
        $order = $this->orderModel->select('orders.*, services.title as service_name, services.description as service_description, services.price as service_price, users.full_name, users.email, users.phone, users.company')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->where('orders.id', $orderId)
            ->where('orders.user_id', $userId)
            ->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to('/orders');
        }
        
        // Get order timeline
        $timeline = $this->orderModel->getTimeline($orderId);
        
        // Get related invoices
        $invoices = $this->invoiceModel->where('order_id', $orderId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Detail Pesanan #' . $order['order_number'] . ' - CENDRATAMA',
            'order' => $order,
            'timeline' => $timeline,
            'invoices' => $invoices
        ];
        
        return view('user/order_detail', $data);
    }

    /**
     * Cancel Order
     */
    public function cancelOrder($orderId)
    {
        $userId = session()->get('user_id');
        
        $order = $this->orderModel->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to('/orders');
        }
        
        // Check if order can be cancelled
        if (!in_array($order['status'], ['pending', 'processing'])) {
            session()->setFlashdata('error', 'Pesanan tidak dapat dibatalkan karena sudah ' . $order['status']);
            return redirect()->to('/orders/' . $orderId);
        }
        
        // Update order status
        if ($this->orderModel->update($orderId, [
            'status' => 'cancelled',
            'cancelled_at' => date('Y-m-d H:i:s'),
            'cancellation_reason' => $this->request->getPost('reason'),
            'updated_at' => date('Y-m-d H:i:s')
        ])) {
            session()->setFlashdata('success', 'Pesanan berhasil dibatalkan.');
            
            // Send notification email
            $email = \Config\Services::email();
            
            $message = view('emails/order_cancelled', [
                'name' => session()->get('user_name'),
                'order_number' => $order['order_number'],
                'reason' => $this->request->getPost('reason')
            ]);
            
            $email->setTo(session()->get('user_email'));
            $email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
            $email->setSubject('Pesanan Dibatalkan #' . $order['order_number'] . ' - CENDRATAMA');
            $email->setMessage($message);
            $email->send();
            
            // Log activity
            $this->logActivity($userId, 'cancel_order', 'User membatalkan pesanan #' . $order['order_number']);
        } else {
            session()->setFlashdata('error', 'Gagal membatalkan pesanan. Silakan coba lagi.');
        }
        
        return redirect()->to('/orders/' . $orderId);
    }

    /**
     * Wishlist
     */
    public function wishlist()
    {
        $userId = session()->get('user_id');
        
        // Get wishlist items
        $wishlistItems = $this->wishlistModel->select('wishlist.*, products.title, products.slug, products.price, products.image, products.category, products.stock')
            ->join('products', 'products.id = wishlist.product_id')
            ->where('wishlist.user_id', $userId)
            ->orderBy('wishlist.created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Wishlist Saya - CENDRATAMA',
            'wishlistItems' => $wishlistItems
        ];
        
        return view('user/wishlist', $data);
    }

    /**
     * Add to Wishlist
     */
    public function addToWishlist()
    {
        $userId = session()->get('user_id');
        $productId = $this->request->getPost('product_id');
        
        // Check if product exists
        $product = $this->productModel->find($productId);
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ]);
        }
        
        // Check if already in wishlist
        $exists = $this->wishlistModel->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        
        if ($exists) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Produk sudah ada di wishlist.'
            ]);
        }
        
        // Add to wishlist
        if ($this->wishlistModel->insert([
            'user_id' => $userId,
            'product_id' => $productId,
            'created_at' => date('Y-m-d H:i:s')
        ])) {
            // Log activity
            $this->logActivity($userId, 'add_wishlist', 'User menambahkan produk ke wishlist: ' . $product['title']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke wishlist.',
                'count' => $this->wishlistModel->where('user_id', $userId)->countAllResults()
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menambahkan ke wishlist.'
        ]);
    }

    /**
     * Remove from Wishlist
     */
    public function removeFromWishlist()
    {
        $userId = session()->get('user_id');
        $wishlistId = $this->request->getPost('wishlist_id');
        
        // Check if wishlist item belongs to user
        $wishlistItem = $this->wishlistModel->where('id', $wishlistId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$wishlistItem) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item wishlist tidak ditemukan.'
            ]);
        }
        
        // Remove from wishlist
        if ($this->wishlistModel->delete($wishlistId)) {
            // Log activity
            $product = $this->productModel->find($wishlistItem['product_id']);
            $this->logActivity($userId, 'remove_wishlist', 'User menghapus produk dari wishlist: ' . ($product['title'] ?? 'Unknown'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari wishlist.',
                'count' => $this->wishlistModel->where('user_id', $userId)->countAllResults()
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus dari wishlist.'
        ]);
    }

    /**
     * Consultations
     */
    public function consultations()
    {
        $userId = session()->get('user_id');
        
        // Get consultations
        $consultations = $this->consultationModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Riwayat Konsultasi - CENDRATAMA',
            'consultations' => $consultations
        ];
        
        return view('user/consultations', $data);
    }

    /**
     * Consultation Detail
     */
    public function consultationDetail($consultationId)
    {
        $userId = session()->get('user_id');
        
        $consultation = $this->consultationModel->where('id', $consultationId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$consultation) {
            session()->setFlashdata('error', 'Konsultasi tidak ditemukan.');
            return redirect()->to('/consultations');
        }
        
        $data = [
            'title' => 'Detail Konsultasi - CENDRATAMA',
            'consultation' => $consultation
        ];
        
        return view('user/consultation_detail', $data);
    }

    /**
     * Invoices
     */
    public function invoices()
    {
        $userId = session()->get('user_id');
        
        // Get invoices
        $invoices = $this->invoiceModel->select('invoices.*, orders.order_number, services.title as service_name')
            ->join('orders', 'orders.id = invoices.order_id')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->where('orders.user_id', $userId)
            ->orderBy('invoices.created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Invoice Saya - CENDRATAMA',
            'invoices' => $invoices
        ];
        
        return view('user/invoices', $data);
    }

    /**
     * View Invoice
     */
    public function viewInvoice($invoiceId)
    {
        $userId = session()->get('user_id');
        
        $invoice = $this->invoiceModel->select('invoices.*, orders.order_number, orders.total_amount, orders.created_at as order_date, users.full_name, users.email, users.phone, users.company, users.address, services.title as service_name, services.description as service_description')
            ->join('orders', 'orders.id = invoices.order_id')
            ->join('users', 'users.id = orders.user_id')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->where('invoices.id', $invoiceId)
            ->where('orders.user_id', $userId)
            ->first();
        
        if (!$invoice) {
            session()->setFlashdata('error', 'Invoice tidak ditemukan.');
            return redirect()->to('/invoices');
        }
        
        $data = [
            'title' => 'Invoice #' . $invoice['invoice_number'] . ' - CENDRATAMA',
            'invoice' => $invoice,
            'company' => [
                'name' => 'PT Cendrawasih Digikarya Pertama',
                'address' => 'Jl. Teknologi No. 123, Jakarta, Indonesia',
                'phone' => '+62 21 1234 5678',
                'email' => 'invoice@cendratama.co.id',
                'website' => 'https://cendratama.co.id'
            ]
        ];
        
        // Return as PDF if requested
        if ($this->request->getGet('download') == 'pdf') {
            return $this->generateInvoicePDF($data);
        }
        
        return view('user/invoice_detail', $data);
    }

    /**
     * Generate Invoice PDF
     */
    private function generateInvoicePDF($data)
    {
        // Load TCPDF library
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('CENDRATAMA');
        $pdf->SetAuthor('CENDRATAMA');
        $pdf->SetTitle('Invoice #' . $data['invoice']['invoice_number']);
        $pdf->SetSubject('Invoice');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Generate HTML content
        $html = view('pdf/invoice', $data);
        
        // Output HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Output PDF
        $pdf->Output('invoice-' . $data['invoice']['invoice_number'] . '.pdf', 'I');
    }

    /**
     * Settings
     */
    public function settings()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'Pengaturan Akun - CENDRATAMA',
            'user' => $user
        ];
        
        return view('user/settings', $data);
    }

    /**
     * Update Settings
     */
    public function updateSettings()
    {
        $userId = session()->get('user_id');
        
        $settings = [
            'email_notifications' => $this->request->getPost('email_notifications') ? 1 : 0,
            'sms_notifications' => $this->request->getPost('sms_notifications') ? 1 : 0,
            'newsletter_subscription' => $this->request->getPost('newsletter_subscription') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($userId, $settings)) {
            session()->setFlashdata('success', 'Pengaturan berhasil diperbarui.');
            
            // Log activity
            $this->logActivity($userId, 'update_settings', 'User memperbarui pengaturan akun');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui pengaturan.');
        }
        
        return redirect()->to('/settings');
    }

    /**
     * Checkout Process
     */
    public function checkout()
    {
        // Verify user is authenticated and verified
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('redirect', current_url());
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        // Check if user is verified
        if ($user['status'] !== 'active' || !$user['verified_at']) {
            session()->setFlashdata('error', 'Silakan verifikasi email terlebih dahulu.');
            return redirect()->to('/verification-required');
        }
        
        $serviceId = $this->request->getPost('service_id');
        $productId = $this->request->getPost('product_id');
        
        if (!$serviceId && !$productId) {
            session()->setFlashdata('error', 'Pilih layanan atau produk terlebih dahulu.');
            return redirect()->back();
        }
        
        $data = [
            'title' => 'Checkout - CENDRATAMA',
            'user' => $user,
            'service' => $serviceId ? $this->serviceModel->find($serviceId) : null,
            'product' => $productId ? $this->productModel->find($productId) : null,
            'validation' => $this->validation
        ];
        
        return view('user/checkout', $data);
    }

    /**
     * Process Checkout
     */
    public function processCheckout()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'service_id' => 'required_without[product_id]',
            'product_id' => 'required_without[service_id]',
            'quantity' => 'required|integer|greater_than[0]',
            'notes' => 'max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Calculate total amount
        $totalAmount = 0;
        $service = null;
        $product = null;
        
        if ($serviceId = $this->request->getPost('service_id')) {
            $service = $this->serviceModel->find($serviceId);
            if ($service) {
                $totalAmount = $service['price'] * $this->request->getPost('quantity');
            }
        }
        
        if ($productId = $this->request->getPost('product_id')) {
            $product = $this->productModel->find($productId);
            if ($product) {
                $totalAmount = $product['price'] * $this->request->getPost('quantity');
            }
        }
        
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        
        // Create order
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => $userId,
            'service_id' => $serviceId,
            'product_id' => $productId,
            'quantity' => $this->request->getPost('quantity'),
            'total_amount' => $totalAmount,
            'notes' => $this->request->getPost('notes'),
            'status' => 'pending',
            'payment_status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($orderId = $this->orderModel->insert($orderData)) {
            // Create invoice
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'status' => 'unpaid',
                'due_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->invoiceModel->insert($invoiceData);
            
            // Send confirmation email
            $email = \Config\Services::email();
            
            $message = view('emails/order_confirmation', [
                'name' => session()->get('user_name'),
                'order_number' => $orderNumber,
                'service' => $service,
                'product' => $product,
                'total_amount' => $totalAmount
            ]);
            
            $email->setTo(session()->get('user_email'));
            $email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
            $email->setSubject('Konfirmasi Pesanan #' . $orderNumber . ' - CENDRATAMA');
            $email->setMessage($message);
            $email->send();
            
            session()->setFlashdata('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            
            // Log activity
            $this->logActivity($userId, 'create_order', 'User membuat pesanan #' . $orderNumber);
            
            return redirect()->to('/orders/' . $orderId);
        }
        
        session()->setFlashdata('error', 'Gagal membuat pesanan. Silakan coba lagi.');
        return redirect()->back();
    }

    /**
     * Premium Features (for verified users only)
     */
    public function premiumFeatures()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'Fitur Premium - CENDRATAMA',
            'user' => $user,
            'premiumFeatures' => [
                [
                    'title' => 'Prioritas Support',
                    'description' => 'Dapatkan dukungan teknis prioritas 24/7',
                    'icon' => 'fas fa-headset',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Diskon Khusus',
                    'description' => 'Dapatkan diskon eksklusif untuk semua layanan',
                    'icon' => 'fas fa-percent',
                    'color' => 'success'
                ],
                [
                    'title' => 'Konsultasi Gratis',
                    'description' => 'Konsultasi IT gratis bulanan dengan ahli',
                    'icon' => 'fas fa-comments',
                    'color' => 'info'
                ],
                [
                    'title' => 'Laporan Bulanan',
                    'description' => 'Laporan kinerja sistem dan rekomendasi',
                    'icon' => 'fas fa-chart-line',
                    'color' => 'warning'
                ]
            ]
        ];
        
        return view('user/premium_features', $data);
    }

    /**
     * Log Activity Helper
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
}