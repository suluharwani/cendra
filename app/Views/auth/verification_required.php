<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="auth-card">
                <div class="auth-header text-center mb-4">
                    <div class="brand-logo mb-3">
                        <div class="brand-container">
                            <span class="brand-icon">C</span>
                            <div class="brand-text">
                                <span class="brand-name">CENDRATAMA</span>
                                <span class="brand-subtitle">Cendrawasih Digikarya Pertama</span>
                            </div>
                        </div>
                    </div>
                    <h2>Verifikasi Email Diperlukan</h2>
                    <p class="text-muted">Silakan verifikasi email Anda untuk melanjutkan</p>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <div class="verification-icon mb-3">
                        <i class="fas fa-envelope fa-4x text-primary"></i>
                    </div>
                    <h4>Halo, <?= esc($user['full_name']) ?>!</h4>
                    <p class="mb-3">Kami telah mengirim email verifikasi ke:</p>
                    <p class="fw-bold mb-4"><?= esc($user['email']) ?></p>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Langkah-langkah verifikasi:</h5>
                        <ol class="mb-0">
                            <li>Cek inbox email Anda</li>
                            <li>Buka email dari CENDRATAMA</li>
                            <li>Klik tombol "Verifikasi Akun"</li>
                            <li>Anda akan diarahkan kembali ke website</li>
                        </ol>
                    </div>
                </div>

                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Perhatian</h6>
                    <ul class="mb-0">
                        <li>Email verifikasi mungkin masuk ke folder spam</li>
                        <li>Link verifikasi hanya berlaku 24 jam</li>
                        <li>Setelah verifikasi, Anda dapat mengakses semua fitur</li>
                    </ul>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-md-6">
                        <form action="<?= base_url('resend-verification') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Ulang Email
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('logout') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-12">
                        <a href="<?= base_url('profile') ?>" class="btn btn-primary w-100">
                            <i class="fas fa-user-edit me-2"></i>Ganti Email
                        </a>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-2">Butuh bantuan?</p>
                    <a href="<?= base_url('contact') ?>" class="text-decoration-none">
                        <i class="fas fa-headset me-2"></i>Hubungi Customer Service
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>