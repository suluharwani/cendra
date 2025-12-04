<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
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
                    <h2>Masuk ke Akun</h2>
                    <p class="text-muted">Silakan masuk untuk melanjutkan</p>
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

                <form action="<?= base_url('login/process') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               value="<?= old('email') ?>" 
                               required>
                        <?php if($validation->hasError('email')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('email') ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if($validation->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3 form-check d-flex justify-content-between">
                        <div>
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none">
                            Lupa Password?
                        </a>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>
                    </div>
                </form>

                <div class="text-center mb-4">
                    <p class="mb-0">Belum punya akun? 
                        <a href="<?= base_url('register') ?>" class="text-decoration-none fw-bold">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>

                <div class="separator">atau masuk dengan</div>

                <div class="social-login mt-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-secondary w-100">
                                <i class="fab fa-google me-2"></i>Google
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-outline-secondary w-100">
                                <i class="fab fa-facebook me-2"></i>Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Auto login with remember me token
document.addEventListener('DOMContentLoaded', function() {
    const rememberMe = getCookie('remember_me');
    if (rememberMe && !document.querySelector('#email').value) {
        // Auto fill email if available in cookie
        const savedEmail = getCookie('saved_email');
        if (savedEmail) {
            document.querySelector('#email').value = savedEmail;
            document.querySelector('#remember').checked = true;
        }
    }
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
</script>
<?= $this->endSection() ?>