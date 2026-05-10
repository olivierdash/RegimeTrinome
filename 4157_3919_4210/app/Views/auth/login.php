<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Connexion') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body class="auth-page">
    <main class="container py-5">
        <div class="auth-card mx-auto">
            <h1 class="h3 fw-bold mb-2">Connexion utilisateur</h1>
            <p class="text-muted mb-4">Accede a tes regimes, ton IMC et ton porte-monnaie.</p>

            <?= view('partials/flash') ?>

            <?php if (! empty($errors ?? [])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('login') ?>" class="vstack gap-3">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($emailValue ?? '') ?>" required>
                </div>
                <div>
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="mot_de_passe" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Se connecter</button>
            </form>

            <div class="d-flex justify-content-between mt-4 small">
                <a href="<?= site_url('register') ?>">Creer un compte</a>
                <a href="<?= site_url('admin/login') ?>">Back-office</a>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
