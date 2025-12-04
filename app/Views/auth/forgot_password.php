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
                    <h2>Lupa Password</h2>
                    <p class="text-muted">Masukkan email Anda untuk reset password</p>
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

                <form action="<?= base_url('forgot-password/process') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                               id="email" 
                               name="email" 
                               value="<?= old('email') ?>" 
                               placeholder="nama@contoh.com"
                               required>
                        <?php if($validation->hasError('email')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('email') ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-text">
                            Kami akan mengirim link reset password ke email Anda
                        </div>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
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