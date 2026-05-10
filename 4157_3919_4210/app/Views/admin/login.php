<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Back-office') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body class="auth-page">
    <main class="container py-5">
        <div class="auth-card mx-auto">
            <h1 class="h3 fw-bold mb-2">Administration</h1>
            <p class="text-muted mb-4">Connexion back-office.</p>
            <?= view('partials/flash') ?>
            <form method="post" action="<?= site_url('admin/login') ?>" class="vstack gap-3">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($values['email'] ?? 'admin@regime.local') ?>" required>
                </div>
                <div>
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="mot_de_passe" class="form-control" required>
                </div>
                <button class="btn btn-dark w-100" type="submit">Entrer</button>
            </form>
            <p class="small text-muted mt-4 mb-0"><a href="<?= site_url('login') ?>">Retour front-office</a></p>
        </div>
    </main>
</body>
</html>
