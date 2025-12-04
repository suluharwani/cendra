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
                    <h2>Reset Password</h2>
                    <p class="text-muted">Buat password baru untuk akun Anda</p>
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

                <form action="<?= base_url('reset-password/process') ?>" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="token" value="<?= $token ?>">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
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

                    <div class="mb-4">
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

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-key me-2"></i>Reset Password
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-0">
                        <a href="<?= base_url('login') ?>" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (!strengthIndicator) {
        const indicator = document.createElement('div');
        indicator.id = 'passwordStrength';
        indicator.className = 'mt-2';
        this.parentNode.appendChild(indicator);
    }
    
    const strength = checkPasswordStrength(password);
    const strengthText = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const strengthColors = ['#dc3545', '#ffc107', '#fd7e14', '#20c997', '#198754'];
    
    document.getElementById('passwordStrength').innerHTML = `
        <div class="strength-meter">
            <div class="strength-meter-fill" style="width: ${(strength + 1) * 20}%; background-color: ${strengthColors[strength]}"></div>
        </div>
        <div class="strength-text text-muted">Kekuatan password: <span style="color: ${strengthColors[strength]}">${strengthText[strength]}</span></div>
    `;
});

function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    return Math.min(4, Math.floor(strength / 2));
}
</script>
<?= $this->endSection() ?>