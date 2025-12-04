<?php

use App\Controllers\Home;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\UserController;

// Public Routes (No Filter)
$routes->get('/', [Home::class, 'index']);
$routes->get('layanan', [Home::class, 'layanan']);
$routes->get('layanan/(:segment)', [Home::class, 'layananDetail/$1']);
$routes->get('produk', [Home::class, 'produk']);
$routes->get('produk/(:segment)', [Home::class, 'produkDetail/$1']);
$routes->get('portfolio', [Home::class, 'portfolio']);
$routes->get('blog', [Home::class, 'blog']);
$routes->get('blog/(:segment)', [Home::class, 'blogDetail/$1']);
$routes->get('tentang', [Home::class, 'tentang']);
$routes->get('kontak', [Home::class, 'kontak']);
$routes->post('kontak/send', [Home::class, 'sendContact']);
$routes->get('faq', [Home::class, 'faq']);
$routes->get('testimoni', [Home::class, 'testimoni']);
$routes->get('klien', [Home::class, 'klien']);
$routes->get('karir', [Home::class, 'karir']);
$routes->get('privacy', [Home::class, 'privacy']);
$routes->get('terms', [Home::class, 'terms']);
$routes->get('syarat-ketentuan', [Home::class, 'terms']);
$routes->get('kebijakan-privasi', [Home::class, 'privacy']);

// Consultation Routes (No Filter)
$routes->get('konsultasi', [Home::class, 'konsultasi']);
$routes->post('konsultasi/book', [Home::class, 'bookKonsultasi']);

// Search Route (No Filter)
$routes->get('search', [Home::class, 'search']);

// Newsletter Route (No Filter)
$routes->post('newsletter/subscribe', [Home::class, 'subscribeNewsletter']);

// Auth Routes with Guest Filter
$routes->group('', ['filter' => 'guest'], function($routes) {
    $routes->get('login', [AuthController::class, 'login']);
    $routes->post('login/process', [AuthController::class, 'processLogin']);
    $routes->get('register', [AuthController::class, 'register']);
    $routes->post('register/process', [AuthController::class, 'processRegister']);
    $routes->get('forgot-password', [AuthController::class, 'forgotPassword']);
    $routes->post('forgot-password/process', [AuthController::class, 'processForgotPassword']);
    $routes->get('reset-password/(:any)', [AuthController::class, 'resetPassword/$1']);
    $routes->post('reset-password/process', [AuthController::class, 'processResetPassword']);
    $routes->get('verify/(:any)', [AuthController::class, 'verify/$1']);
});

// Auth Routes without Filter (accessible by all)
$routes->get('logout', [AuthController::class, 'logout']);

// Verification Required Page (Auth Filter)
$routes->get('verification-required', [AuthController::class, 'verificationRequired'], ['filter' => 'auth']);

// User Dashboard Routes with Auth Filter
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', [UserController::class, 'dashboard']);
    $routes->get('profile', [UserController::class, 'profile']);
    $routes->post('profile/update', [UserController::class, 'updateProfile']);
    $routes->post('change-password', [UserController::class, 'changePassword']);
    $routes->get('resend-verification', [AuthController::class, 'resendVerification']);
    
    // Orders Routes
    $routes->get('orders', [UserController::class, 'orders']);
    $routes->get('orders/(:num)', [UserController::class, 'orderDetail/$1']);
    $routes->post('orders/cancel/(:num)', [UserController::class, 'cancelOrder/$1']);
    
    // Wishlist Routes
    $routes->get('wishlist', [UserController::class, 'wishlist']);
    $routes->post('wishlist/add', [UserController::class, 'addToWishlist']);
    $routes->post('wishlist/remove', [UserController::class, 'removeFromWishlist']);
    
    // Consultation History
    $routes->get('consultations', [UserController::class, 'consultations']);
    $routes->get('consultations/(:num)', [UserController::class, 'consultationDetail/$1']);
});

// User Routes with Verified Filter
$routes->group('', ['filter' => 'verified'], function($routes) {
    $routes->post('checkout', [UserController::class, 'checkout']);
    $routes->get('invoices/(:num)', [UserController::class, 'viewInvoice/$1']);
    $routes->get('premium-features', [UserController::class, 'premiumFeatures']);
});

// Admin Routes with Admin Filter
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', [AdminController::class, 'dashboard']);
    
    // User Management
    $routes->get('users', [AdminController::class, 'users']);
    $routes->get('users/(:num)', [AdminController::class, 'userDetail/$1']);
    $routes->post('users/update-status/(:num)', [AdminController::class, 'updateUserStatus/$1']);
    $routes->post('users/delete/(:num)', [AdminController::class, 'deleteUser/$1']);
    
    // Services Management
    $routes->get('services', [AdminController::class, 'services']);
    $routes->get('services/create', [AdminController::class, 'createService']);
    $routes->post('services/store', [AdminController::class, 'storeService']);
    $routes->get('services/edit/(:num)', [AdminController::class, 'editService/$1']);
    $routes->post('services/update/(:num)', [AdminController::class, 'updateService/$1']);
    $routes->post('services/delete/(:num)', [AdminController::class, 'deleteService/$1']);
    
    // Products Management
    $routes->get('products', [AdminController::class, 'products']);
    $routes->get('products/create', [AdminController::class, 'createProduct']);
    $routes->post('products/store', [AdminController::class, 'storeProduct']);
    $routes->get('products/edit/(:num)', [AdminController::class, 'editProduct/$1']);
    $routes->post('products/update/(:num)', [AdminController::class, 'updateProduct/$1']);
    $routes->post('products/delete/(:num)', [AdminController::class, 'deleteProduct/$1']);
    
    // Orders Management
    $routes->get('orders', [AdminController::class, 'orders']);
    $routes->get('orders/(:num)', [AdminController::class, 'orderDetail/$1']);
    $routes->post('orders/update-status/(:num)', [AdminController::class, 'updateOrderStatus/$1']);
    
    // Blog Management
    $routes->get('blog', [AdminController::class, 'blogPosts']);
    $routes->get('blog/create', [AdminController::class, 'createBlogPost']);
    $routes->post('blog/store', [AdminController::class, 'storeBlogPost']);
    $routes->get('blog/edit/(:num)', [AdminController::class, 'editBlogPost/$1']);
    $routes->post('blog/update/(:num)', [AdminController::class, 'updateBlogPost/$1']);
    $routes->post('blog/delete/(:num)', [AdminController::class, 'deleteBlogPost/$1']);
    
    // Settings
    $routes->get('settings', [AdminController::class, 'settings']);
    $routes->post('settings/update', [AdminController::class, 'updateSettings']);
    
    // Reports
    $routes->get('reports', [AdminController::class, 'reports']);
    $routes->get('reports/sales', [AdminController::class, 'salesReport']);
    $routes->get('reports/users', [AdminController::class, 'usersReport']);
});

// API Routes with Throttle Filter
$routes->group('api', ['filter' => 'throttle'], function($routes) {
    $routes->post('calculate-price', 'ApiController::calculatePrice');
    $routes->get('products', 'ApiController::getProducts');
    $routes->get('services', 'ApiController::getServices');
    $routes->post('contact', 'ApiController::sendContact');
    $routes->post('newsletter', 'ApiController::subscribeNewsletter');
    
    // Auth API Routes
    $routes->post('auth/login', 'ApiAuthController::login');
    $routes->post('auth/register', 'ApiAuthController::register');
    $routes->post('auth/forgot-password', 'ApiAuthController::forgotPassword');
    $routes->post('auth/reset-password', 'ApiAuthController::resetPassword');
    
    // Protected API Routes
    $routes->group('', ['filter' => 'auth:api'], function($routes) {
        $routes->get('user/profile', 'ApiUserController::profile');
        $routes->put('user/profile', 'ApiUserController::updateProfile');
        $routes->get('user/orders', 'ApiUserController::orders');
        $routes->post('user/wishlist', 'ApiUserController::addToWishlist');
    });
});

// Webhook Routes (No CSRF protection)
$routes->group('webhook', ['filter' => 'throttle'], function($routes) {
    $routes->post('payment/callback', 'WebhookController::paymentCallback');
    $routes->post('email/status', 'WebhookController::emailStatus');
});

// Catch-all route for 404
$routes->set404Override(function() {
    return view('errors/html/error_404');
});

// Maintenance Mode (Uncomment when needed)
// $routes->setDefaultNamespace('App\Controllers');
// $routes->get('(:any)', 'Home::maintenance');
// $routes->post('(:any)', 'Home::maintenance');