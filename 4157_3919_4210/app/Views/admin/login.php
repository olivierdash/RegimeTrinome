<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
    body {
        background-color: #f4f7f9;
    }
</style>

<div class="login-container px-3">
    <div class="card login-card">
        <div class="login-header">
            <div class="mb-3">
                <i class="bi bi-shield-lock fs-1"></i>
            </div>
            <h1>ADMINISTRATION</h1>
            <p class="small text-white-50 mb-0">Veuillez vous identifier pour continuer</p>
        </div>
        
        <div class="card-body p-4 p-md-5 bg-white">
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger py-2 mb-4 border-0 rounded-3 small">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php foreach ($errors as $error): ?>
                        <?= esc($error) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('admin/login') ?>">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email professionnel</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" 
                               name="email" 
                               class="form-control bg-light border-start-0 ps-0" 
                               value="<?= $values['email'] ?? 'admin@regime.local' ?>" 
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                        <input type="password" 
                               name="mot_de_passe" 
                               class="form-control bg-light border-start-0 ps-0" 
                               placeholder="••••••••" 
                               required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark btn-login text-white">
                        Connexion au Dashboard
                    </button>
                </div>
            </form>
        </div>
        
        <div class="card-footer bg-light border-0 py-3 text-center">
            <a href="<?= site_url('/') ?>" class="text-decoration-none small text-muted">
                <i class="bi bi-arrow-left me-1"></i> Retour au site
            </a>
        </div>
    </div>
</div>
</body>
</html>