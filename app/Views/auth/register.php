<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
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
                    <h2>Daftar Akun Baru</h2>
                    <p class="text-muted">Bergabung dengan CENDRATAMA untuk mendapatkan layanan terbaik</p>
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

                <form action="<?= base_url('register/process') ?>" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= ($validation->hasError('full_name')) ? 'is-invalid' : '' ?>" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="<?= old('full_name') ?>" 
                                   required>
                            <?php if($validation->hasError('full_name')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('full_name') ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
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
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="tel" 
                                   class="form-control <?= ($validation->hasError('phone')) ? 'is-invalid' : '' ?>" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?= old('phone') ?>" 
                                   required>
                            <?php if($validation->hasError('phone')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('phone') ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">Nama Perusahaan (Opsional)</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="company" 
                                   name="company" 
                                   value="<?= old('company') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
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
                            <div class="form-text">
                                Password minimal 6 karakter
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control <?= ($validation->hasError('confirm_password')) ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if($validation->hasError('confirm_password')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('confirm_password') ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input <?= ($validation->hasError('terms')) ? 'is-invalid' : '' ?>" 
                                   type="checkbox" 
                                   id="terms" 
                                   name="terms" 
                                   required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui <a href="<?= base_url('terms') ?>" target="_blank">Syarat & Ketentuan</a> 
                                dan <a href="<?= base_url('privacy') ?>" target="_blank">Kebijakan Privasi</a>
                            </label>
                            <?php if($validation->hasError('terms')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('terms') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-0">Sudah punya akun? 
                        <a href="<?= base_url('login') ?>" class="text-decoration-none fw-bold">
                            Masuk Sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Toggle password visibility
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

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const confirmInput = document.getElementById('confirm_password');
    const icon = this.querySelector('i');
    
    if (confirmInput.type === 'password') {
        confirmInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        confirmInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form validation
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (!strengthIndicator) {
        // Create strength indicator
        const indicator = document.createElement('div');
        indicator.id = 'passwordStrength';
        indicator.className = 'mt-2';
        this.parentNode.appendChild(indicator);
        
        // Add CSS for strength meter
        const style = document.createElement('style');
        style.textContent = `
            .strength-meter {
                height: 5px;
                background-color: #e9ecef;
                border-radius: 3px;
                margin-top: 5px;
                overflow: hidden;
            }
            .strength-meter-fill {
                height: 100%;
                width: 0;
                transition: width 0.3s ease;
                border-radius: 3px;
            }
            .strength-text {
                font-size: 0.8rem;
                margin-top: 2px;
            }
        `;
        document.head.appendChild(style);
    }
    
    const strength = checkPasswordStrength(password);
    const strengthText = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const strengthColors = ['#dc3545', '#ffc107', '#fd7e14', '#20c997', '#198754'];
    
    strengthIndicator.innerHTML = `
        <div class="strength-meter">
            <div class="strength-meter-fill" style="width: ${(strength + 1) * 20}%; background-color: ${strengthColors[strength]}"></div>
        </div>
        <div class="strength-text text-muted">Kekuatan password: <span style="color: ${strengthColors[strength]}">${strengthText[strength]}</span></div>
    `;
});

function checkPasswordStrength(password) {
    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Character type checks
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    // Normalize to 0-4 scale
    return Math.min(4, Math.floor(strength / 2));
}
</script>
<?= $this->endSection() ?>