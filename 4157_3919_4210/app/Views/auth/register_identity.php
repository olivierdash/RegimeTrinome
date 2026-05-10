<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Inscription') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body class="auth-page">
    <main class="container py-5">
        <div class="auth-card mx-auto">
            <span class="text-muted small">Etape 1 sur 2</span>
            <h1 class="h3 fw-bold mb-4">Informations du compte</h1>

            <?php if (! empty($errors ?? [])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('register') ?>" class="vstack gap-3">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Nom</label>
                    <input name="nom" class="form-control" value="<?= esc($values['nom'] ?? '') ?>" required>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($values['email'] ?? '') ?>" required>
                </div>
                <div>
                    <label class="form-label">Genre</label>
                    <select name="genre" class="form-select" required>
                        <option value="">Choisir</option>
                        <option value="Homme" <?= (($values['genre'] ?? '') === 'Homme') ? 'selected' : '' ?>>Homme</option>
                        <option value="Femme" <?= (($values['genre'] ?? '') === 'Femme') ? 'selected' : '' ?>>Femme</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="mot_de_passe" class="form-control" minlength="8" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Continuer</button>
            </form>

            <p class="small text-muted mt-4 mb-0">Deja inscrit ? <a href="<?= site_url('login') ?>">Connexion</a></p>
        </div>
    </main>
</body>
</html>
