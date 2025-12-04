<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\ServiceModel;
use App\Models\ProductModel;
use App\Models\ConsultationModel;
use App\Models\InvoiceModel;
use App\Models\ActivityLogModel;
use App\Models\SettingModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends BaseController
{
    protected $userModel;
    protected $orderModel;
    protected $serviceModel;
    protected $productModel;
    protected $consultationModel;
    protected $invoiceModel;
    protected $activityModel;
    protected $settingModel;
    protected $validation;

    public function __construct()
    {
        helper(['form', 'url', 'session', 'number', 'text', 'date']);
        
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->serviceModel = new ServiceModel();
        $this->productModel = new ProductModel();
        $this->consultationModel = new ConsultationModel();
        $this->invoiceModel = new InvoiceModel();
        $this->activityModel = new ActivityLogModel();
        $this->settingModel = new SettingModel();
        $this->validation = \Config\Services::validation();
        
        // Check if user is admin
        if (session()->get('user_role') != 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_users' => $this->userModel->countAll(),
            'total_orders' => $this->orderModel->countAll(),
            'total_services' => $this->serviceModel->countAll(),
            'total_products' => $this->productModel->countAll(),
            'total_revenue' => $this->orderModel->selectSum('total_amount')
                ->where('status', 'completed')
                ->get()
                ->getRow()->total_amount ?? 0,
            'pending_orders' => $this->orderModel->where('status', 'pending')->countAllResults(),
            'today_orders' => $this->orderModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
            'today_revenue' => $this->orderModel->selectSum('total_amount')
                ->where('DATE(created_at)', date('Y-m-d'))
                ->where('status', 'completed')
                ->get()
                ->getRow()->total_amount ?? 0
        ];
        
        // Get recent orders
        $recentOrders = $this->orderModel->select('orders.*, users.full_name, services.title as service_name')
            ->join('users', 'users.id = orders.user_id')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->orderBy('orders.created_at', 'DESC')
            ->limit(10)
            ->find();
        
        // Get recent users
        $recentUsers = $this->userModel->orderBy('created_at', 'DESC')
            ->limit(10)
            ->find();
        
        // Get sales data for chart
        $salesData = $this->getSalesData();
        
        $data = [
            'title' => 'Admin Dashboard - CENDRATAMA',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentUsers' => $recentUsers,
            'salesData' => $salesData
        ];
        
        return view('admin/dashboard', $data);
    }

    /**
     * Get Sales Data for Chart
     */
    private function getSalesData($period = 'monthly')
    {
        $data = [];
        
        if ($period == 'monthly') {
            // Get last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthName = date('M Y', strtotime($month . '-01'));
                
                $result = $this->orderModel->selectSum('total_amount')
                    ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                    ->where('status', 'completed')
                    ->get()
                    ->getRow();
                
                $data['labels'][] = $monthName;
                $data['values'][] = $result->total_amount ?? 0;
            }
        }
        
        return $data;
    }

    /**
     * User Management
     */
    public function users()
    {
        // Get filter parameters
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 20;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->userModel;
        
        // Apply filters
        if ($role && $role != 'all') {
            $builder->where('role', $role);
        }
        
        if ($status && $status != 'all') {
            $builder->where('status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('full_name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }
        
        // Get total for pagination
        $total = $builder->countAllResults(false);
        
        // Get users with pagination
        $users = $builder->orderBy('created_at', 'DESC')
            ->paginate($limit, 'default', $page);
        
        $pager = $this->userModel->pager;
        
        $data = [
            'title' => 'Manajemen User - CENDRATAMA',
            'users' => $users,
            'pager' => $pager,
            'total' => $total,
            'role' => $role,
            'status' => $status,
            'search' => $search,
            'limit' => $limit
        ];
        
        return view('admin/users/index', $data);
    }

    /**
     * User Detail
     */
    public function userDetail($userId)
    {
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/admin/users');
        }
        
        // Get user orders
        $orders = $this->orderModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Get user activity logs
        $activities = $this->activityModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->find();
        
        $data = [
            'title' => 'Detail User - ' . $user['full_name'] . ' - CENDRATAMA',
            'user' => $user,
            'orders' => $orders,
            'activities' => $activities
        ];
        
        return view('admin/users/detail', $data);
    }

    /**
     * Update User Status
     */
    public function updateUserStatus($userId)
    {
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ]);
        }
        
        $status = $this->request->getPost('status');
        $validStatuses = ['active', 'pending', 'suspended', 'banned'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status tidak valid.'
            ]);
        }
        
        $updateData = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->update($userId, $updateData)) {
            // Log activity
            $this->logAdminActivity('update_user_status', 
                'Admin mengubah status user ' . $user['email'] . ' menjadi ' . $status);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status user berhasil diperbarui.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal memperbarui status user.'
        ]);
    }

    /**
     * Delete User
     */
    public function deleteUser($userId)
    {
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to('/admin/users');
        }
        
        // Prevent deleting own account
        if ($userId == session()->get('user_id')) {
            session()->setFlashdata('error', 'Tidak dapat menghapus akun sendiri.');
            return redirect()->to('/admin/users');
        }
        
        if ($this->userModel->delete($userId)) {
            // Log activity
            $this->logAdminActivity('delete_user', 
                'Admin menghapus user ' . $user['email']);
            
            session()->setFlashdata('success', 'User berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus user.');
        }
        
        return redirect()->to('/admin/users');
    }

    /**
     * Services Management
     */
    public function services()
    {
        // Get filter parameters
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 20;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->serviceModel;
        
        // Apply filters
        if ($category && $category != 'all') {
            $builder->where('category', $category);
        }
        
        if ($status && $status != 'all') {
            $builder->where('status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('title', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }
        
        // Get total for pagination
        $total = $builder->countAllResults(false);
        
        // Get services with pagination
        $services = $builder->orderBy('created_at', 'DESC')
            ->paginate($limit, 'default', $page);
        
        $pager = $this->serviceModel->pager;
        
        $data = [
            'title' => 'Manajemen Layanan - CENDRATAMA',
            'services' => $services,
            'pager' => $pager,
            'total' => $total,
            'categories' => $this->getServiceCategories(),
            'category' => $category,
            'status' => $status,
            'search' => $search,
            'limit' => $limit
        ];
        
        return view('admin/services/index', $data);
    }

    /**
     * Create Service
     */
    public function createService()
    {
        $data = [
            'title' => 'Tambah Layanan Baru - CENDRATAMA',
            'categories' => $this->getServiceCategories(),
            'validation' => $this->validation
        ];
        
        return view('admin/services/create', $data);
    }

    /**
     * Store Service
     */
    public function storeService()
    {
        $rules = [
            'title' => [
                'label' => 'Judul Layanan',
                'rules' => 'required|min_length[3]|max_length[200]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter',
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'slug' => [
                'label' => 'Slug',
                'rules' => 'required|alpha_dash|is_unique[services.slug]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'alpha_dash' => '{field} hanya boleh berisi huruf, angka, dash, dan underscore',
                    'is_unique' => '{field} sudah digunakan'
                ]
            ],
            'description' => [
                'label' => 'Deskripsi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter'
                ]
            ],
            'price' => [
                'label' => 'Harga',
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'numeric' => '{field} harus berupa angka',
                    'greater_than' => '{field} harus lebih dari 0'
                ]
            ],
            'category' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus dipilih'
                ]
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[active,inactive]',
                'errors' => [
                    'required' => '{field} harus dipilih',
                    'in_list' => '{field} tidak valid'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Handle image upload
        $image = 'default-service.jpg';
        $imageFile = $this->request->getFile('image');
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $validationRule = [
                'image' => [
                    'label' => 'Gambar',
                    'rules' => 'uploaded[image]'
                        . '|is_image[image]'
                        . '|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'
                        . '|max_size[image,5120]',
                    'errors' => [
                        'uploaded' => 'Pilih {field} terlebih dahulu',
                        'is_image' => '{field} harus berupa gambar',
                        'mime_in' => '{field} harus berformat JPG, JPEG, PNG, atau WebP',
                        'max_size' => 'Ukuran {field} maksimal 5MB'
                    ]
                ]
            ];
            
            if ($this->validate($validationRule)) {
                $newName = $imageFile->getRandomName();
                $imageFile->move(ROOTPATH . 'public/uploads/services', $newName);
                $image = $newName;
            }
        }
        
        // Prepare service data
        $serviceData = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'category' => $this->request->getPost('category'),
            'image' => $image,
            'features' => json_encode(explode("\n", $this->request->getPost('features'))),
            'status' => $this->request->getPost('status'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->serviceModel->insert($serviceData)) {
            // Log activity
            $this->logAdminActivity('create_service', 
                'Admin membuat layanan baru: ' . $serviceData['title']);
            
            session()->setFlashdata('success', 'Layanan berhasil ditambahkan.');
            return redirect()->to('/admin/services');
        }
        
        session()->setFlashdata('error', 'Gagal menambahkan layanan.');
        return redirect()->back()->withInput();
    }

    /**
     * Edit Service
     */
    public function editService($serviceId)
    {
        $service = $this->serviceModel->find($serviceId);
        
        if (!$service) {
            session()->setFlashdata('error', 'Layanan tidak ditemukan.');
            return redirect()->to('/admin/services');
        }
        
        $data = [
            'title' => 'Edit Layanan - CENDRATAMA',
            'service' => $service,
            'categories' => $this->getServiceCategories(),
            'validation' => $this->validation
        ];
        
        return view('admin/services/edit', $data);
    }

    /**
     * Update Service
     */
    public function updateService($serviceId)
    {
        $service = $this->serviceModel->find($serviceId);
        
        if (!$service) {
            session()->setFlashdata('error', 'Layanan tidak ditemukan.');
            return redirect()->to('/admin/services');
        }
        
        $rules = [
            'title' => [
                'label' => 'Judul Layanan',
                'rules' => 'required|min_length[3]|max_length[200]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter',
                    'max_length' => '{field} maksimal {param} karakter'
                ]
            ],
            'slug' => [
                'label' => 'Slug',
                'rules' => 'required|alpha_dash',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'alpha_dash' => '{field} hanya boleh berisi huruf, angka, dash, dan underscore'
                ]
            ],
            'description' => [
                'label' => 'Deskripsi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'min_length' => '{field} minimal {param} karakter'
                ]
            ],
            'price' => [
                'label' => 'Harga',
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => '{field} harus diisi',
                    'numeric' => '{field} harus berupa angka',
                    'greater_than' => '{field} harus lebih dari 0'
                ]
            ],
            'category' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus dipilih'
                ]
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[active,inactive]',
                'errors' => [
                    'required' => '{field} harus dipilih',
                    'in_list' => '{field} tidak valid'
                ]
            ]
        ];
        
        // Check slug uniqueness if changed
        $newSlug = $this->request->getPost('slug');
        if ($newSlug != $service['slug']) {
            $rules['slug']['rules'] .= '|is_unique[services.slug]';
            $rules['slug']['errors']['is_unique'] = '{field} sudah digunakan';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Handle image upload
        $image = $service['image'];
        $imageFile = $this->request->getFile('image');
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $validationRule = [
                'image' => [
                    'label' => 'Gambar',
                    'rules' => 'uploaded[image]'
                        . '|is_image[image]'
                        . '|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'
                        . '|max_size[image,5120]',
                    'errors' => [
                        'uploaded' => 'Pilih {field} terlebih dahulu',
                        'is_image' => '{field} harus berupa gambar',
                        'mime_in' => '{field} harus berformat JPG, JPEG, PNG, atau WebP',
                        'max_size' => 'Ukuran {field} maksimal 5MB'
                    ]
                ]
            ];
            
            if ($this->validate($validationRule)) {
                $newName = $imageFile->getRandomName();
                $imageFile->move(ROOTPATH . 'public/uploads/services', $newName);
                
                // Delete old image if not default
                if ($image && $image != 'default-service.jpg' && file_exists(ROOTPATH . 'public/uploads/services/' . $image)) {
                    unlink(ROOTPATH . 'public/uploads/services/' . $image);
                }
                
                $image = $newName;
            }
        }
        
        // Prepare update data
        $updateData = [
            'title' => $this->request->getPost('title'),
            'slug' => $newSlug,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'category' => $this->request->getPost('category'),
            'image' => $image,
            'features' => json_encode(explode("\n", $this->request->getPost('features'))),
            'status' => $this->request->getPost('status'),
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->serviceModel->update($serviceId, $updateData)) {
            // Log activity
            $this->logAdminActivity('update_service', 
                'Admin memperbarui layanan: ' . $updateData['title']);
            
            session()->setFlashdata('success', 'Layanan berhasil diperbarui.');
            return redirect()->to('/admin/services');
        }
        
        session()->setFlashdata('error', 'Gagal memperbarui layanan.');
        return redirect()->back()->withInput();
    }

    /**
     * Delete Service
     */
    public function deleteService($serviceId)
    {
        $service = $this->serviceModel->find($serviceId);
        
        if (!$service) {
            session()->setFlashdata('error', 'Layanan tidak ditemukan.');
            return redirect()->to('/admin/services');
        }
        
        if ($this->serviceModel->delete($serviceId)) {
            // Delete image if not default
            if ($service['image'] && $service['image'] != 'default-service.jpg' && 
                file_exists(ROOTPATH . 'public/uploads/services/' . $service['image'])) {
                unlink(ROOTPATH . 'public/uploads/services/' . $service['image']);
            }
            
            // Log activity
            $this->logAdminActivity('delete_service', 
                'Admin menghapus layanan: ' . $service['title']);
            
            session()->setFlashdata('success', 'Layanan berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus layanan.');
        }
        
        return redirect()->to('/admin/services');
    }

    /**
     * Products Management
     */
    public function products()
    {
        // Similar to services management
        // Implementation would be similar to services()
        return view('admin/products/index', [
            'title' => 'Manajemen Produk - CENDRATAMA'
        ]);
    }

    /**
     * Orders Management
     */
    public function orders()
    {
        // Get filter parameters
        $status = $this->request->getGet('status');
        $payment_status = $this->request->getGet('payment_status');
        $date_from = $this->request->getGet('date_from');
        $date_to = $this->request->getGet('date_to');
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit') ?? 20;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->orderModel->select('orders.*, users.full_name, users.email, services.title as service_name')
            ->join('users', 'users.id = orders.user_id')
            ->join('services', 'services.id = orders.service_id', 'left');
        
        // Apply filters
        if ($status && $status != 'all') {
            $builder->where('orders.status', $status);
        }
        
        if ($payment_status && $payment_status != 'all') {
            $builder->where('orders.payment_status', $payment_status);
        }
        
        if ($date_from) {
            $builder->where('DATE(orders.created_at) >=', $date_from);
        }
        
        if ($date_to) {
            $builder->where('DATE(orders.created_at) <=', $date_to);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('orders.order_number', $search)
                ->orLike('users.full_name', $search)
                ->orLike('users.email', $search)
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
            'title' => 'Manajemen Pesanan - CENDRATAMA',
            'orders' => $orders,
            'pager' => $pager,
            'total' => $total,
            'status' => $status,
            'payment_status' => $payment_status,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'search' => $search,
            'limit' => $limit
        ];
        
        return view('admin/orders/index', $data);
    }

    /**
     * Order Detail (Admin)
     */
    public function orderDetail($orderId)
    {
        $order = $this->orderModel->select('orders.*, users.full_name, users.email, users.phone, users.company, users.address, services.title as service_name, services.description as service_description, services.price as service_price')
            ->join('users', 'users.id = orders.user_id')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->where('orders.id', $orderId)
            ->first();
        
        if (!$order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to('/admin/orders');
        }
        
        // Get order timeline
        $timeline = $this->orderModel->getTimeline($orderId);
        
        // Get invoices
        $invoices = $this->invoiceModel->where('order_id', $orderId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Detail Pesanan #' . $order['order_number'] . ' - CENDRATAMA',
            'order' => $order,
            'timeline' => $timeline,
            'invoices' => $invoices
        ];
        
        return view('admin/orders/detail', $data);
    }

    /**
     * Update Order Status
     */
    public function updateOrderStatus($orderId)
    {
        $order = $this->orderModel->find($orderId);
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ]);
        }
        
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled', 'refunded'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status tidak valid.'
            ]);
        }
        
        $updateData = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Add completed_at if status is completed
        if ($status == 'completed' && $order['status'] != 'completed') {
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        }
        
        if ($this->orderModel->update($orderId, $updateData)) {
            // Send notification to user
            $this->sendOrderStatusNotification($orderId, $status);
            
            // Log activity
            $this->logAdminActivity('update_order_status', 
                'Admin mengubah status pesanan #' . $order['order_number'] . ' menjadi ' . $status);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal memperbarui status pesanan.'
        ]);
    }

    /**
     * Send Order Status Notification
     */
    private function sendOrderStatusNotification($orderId, $status)
    {
        $order = $this->orderModel->select('orders.*, users.email, users.full_name')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.id', $orderId)
            ->first();
        
        if (!$order) return;
        
        $email = \Config\Services::email();
        
        $statusLabels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan'
        ];
        
        $message = view('emails/order_status_update', [
            'name' => $order['full_name'],
            'order_number' => $order['order_number'],
            'old_status' => $statusLabels[$order['status']] ?? $order['status'],
            'new_status' => $statusLabels[$status] ?? $status,
            'updated_at' => date('d F Y H:i')
        ]);
        
        $email->setTo($order['email']);
        $email->setFrom('noreply@cendratama.co.id', 'CENDRATAMA');
        $email->setSubject('Update Status Pesanan #' . $order['order_number'] . ' - CENDRATAMA');
        $email->setMessage($message);
        $email->send();
    }

    /**
     * Blog Management
     */
    public function blogPosts()
    {
        // Implementation similar to services management
        return view('admin/blog/index', [
            'title' => 'Manajemen Blog - CENDRATAMA'
        ]);
    }

    /**
     * Settings
     */
    public function settings()
    {
        $settings = $this->settingModel->findAll();
        $settingsArray = [];
        
        foreach ($settings as $setting) {
            $settingsArray[$setting['key']] = $setting['value'];
        }
        
        $data = [
            'title' => 'Pengaturan Sistem - CENDRATAMA',
            'settings' => $settingsArray
        ];
        
        return view('admin/settings/index', $data);
    }

    /**
     * Update Settings
     */
    public function updateSettings()
    {
        $settings = $this->request->getPost('settings');
        
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                $this->settingModel->setValue($key, $value);
            }
            
            // Log activity
            $this->logAdminActivity('update_settings', 
                'Admin memperbarui pengaturan sistem');
            
            session()->setFlashdata('success', 'Pengaturan berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui pengaturan.');
        }
        
        return redirect()->to('/admin/settings');
    }

    /**
     * Reports
     */
    public function reports()
    {
        $data = [
            'title' => 'Laporan - CENDRATAMA'
        ];
        
        return view('admin/reports/index', $data);
    }

    /**
     * Sales Report
     */
    public function salesReport()
    {
        $period = $this->request->getGet('period') ?? 'monthly';
        $date_from = $this->request->getGet('date_from');
        $date_to = $this->request->getGet('date_to');
        
        // Build query
        $builder = $this->orderModel->select('orders.*, users.full_name, services.title as service_name')
            ->join('users', 'users.id = orders.user_id')
            ->join('services', 'services.id = orders.service_id', 'left')
            ->where('orders.status', 'completed');
        
        // Apply date filter
        if ($date_from) {
            $builder->where('DATE(orders.created_at) >=', $date_from);
        }
        
        if ($date_to) {
            $builder->where('DATE(orders.created_at) <=', $date_to);
        }
        
        // Get sales data
        $sales = $builder->orderBy('orders.created_at', 'DESC')
            ->findAll();
        
        // Calculate totals
        $totalSales = array_sum(array_column($sales, 'total_amount'));
        $totalOrders = count($sales);
        
        // Export to Excel if requested
        if ($this->request->getGet('export') == 'excel') {
            return $this->exportSalesReport($sales);
        }
        
        $data = [
            'title' => 'Laporan Penjualan - CENDRATAMA',
            'sales' => $sales,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'period' => $period,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];
        
        return view('admin/reports/sales', $data);
    }

    /**
     * Export Sales Report to Excel
     */
    private function exportSalesReport($sales)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'No. Pesanan');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Customer');
        $sheet->setCellValue('D1', 'Layanan/Produk');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'Status');
        
        // Fill data
        $row = 2;
        foreach ($sales as $sale) {
            $sheet->setCellValue('A' . $row, $sale['order_number']);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($sale['created_at'])));
            $sheet->setCellValue('C' . $row, $sale['full_name']);
            $sheet->setCellValue('D' . $row, $sale['service_name']);
            $sheet->setCellValue('E' . $row, $sale['quantity']);
            $sheet->setCellValue('F' . $row, $sale['total_amount']);
            $sheet->setCellValue('G' . $row, $sale['status']);
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="laporan-penjualan-' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    /**
     * Users Report
     */
    public function usersReport()
    {
        $date_from = $this->request->getGet('date_from');
        $date_to = $this->request->getGet('date_to');
        $role = $this->request->getGet('role');
        
        // Build query
        $builder = $this->userModel;
        
        // Apply filters
        if ($date_from) {
            $builder->where('DATE(created_at) >=', $date_from);
        }
        
        if ($date_to) {
            $builder->where('DATE(created_at) <=', $date_to);
        }
        
        if ($role && $role != 'all') {
            $builder->where('role', $role);
        }
        
        // Get users
        $users = $builder->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Statistics
        $stats = [
            'total' => count($users),
            'active' => array_filter($users, fn($u) => $u['status'] == 'active'),
            'pending' => array_filter($users, fn($u) => $u['status'] == 'pending'),
            'admins' => array_filter($users, fn($u) => $u['role'] == 'admin')
        ];
        
        $data = [
            'title' => 'Laporan User - CENDRATAMA',
            'users' => $users,
            'stats' => $stats,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'role' => $role
        ];
        
        return view('admin/reports/users', $data);
    }

    /**
     * Activity Logs
     */
    public function activityLogs()
    {
        $userId = $this->request->getGet('user_id');
        $activity = $this->request->getGet('activity');
        $date_from = $this->request->getGet('date_from');
        $date_to = $this->request->getGet('date_to');
        $limit = $this->request->getGet('limit') ?? 50;
        $page = $this->request->getGet('page') ?? 1;
        
        // Build query
        $builder = $this->activityModel->select('activity_logs.*, users.full_name, users.email')
            ->join('users', 'users.id = activity_logs.user_id', 'left');
        
        // Apply filters
        if ($userId) {
            $builder->where('activity_logs.user_id', $userId);
        }
        
        if ($activity) {
            $builder->where('activity_logs.activity', $activity);
        }
        
        if ($date_from) {
            $builder->where('DATE(activity_logs.created_at) >=', $date_from);
        }
        
        if ($date_to) {
            $builder->where('DATE(activity_logs.created_at) <=', $date_to);
        }
        
        // Get total for pagination
        $total = $builder->countAllResults(false);
        
        // Get logs with pagination
        $logs = $builder->orderBy('activity_logs.created_at', 'DESC')
            ->paginate($limit, 'default', $page);
        
        $pager = $this->activityModel->pager;
        
        // Get unique activities for filter
        $activities = $this->activityModel->distinct()
            ->select('activity')
            ->orderBy('activity')
            ->findAll();
        
        $data = [
            'title' => 'Log Aktivitas - CENDRATAMA',
            'logs' => $logs,
            'pager' => $pager,
            'total' => $total,
            'activities' => $activities,
            'userId' => $userId,
            'activity' => $activity,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'limit' => $limit
        ];
        
        return view('admin/activity_logs', $data);
    }

    /**
     * Get Service Categories
     */
    private function getServiceCategories()
    {
        return [
            'website' => 'Website Development',
            'it-support' => 'IT Support',
            'cctv' => 'CCTV Installation',
            'network' => 'Network Setup',
            'software' => 'Software Development',
            'consultation' => 'IT Consultation'
        ];
    }

    /**
     * Log Admin Activity
     */
    private function logAdminActivity($activity, $description)
    {
        $activityData = [
            'user_id' => session()->get('user_id'),
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->activityModel->insert($activityData);
    }
}