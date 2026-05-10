<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sante') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body class="auth-page">
    <main class="container py-5">
        <div class="auth-card mx-auto">
            <span class="text-muted small">Etape 2 sur 2</span>
            <h1 class="h3 fw-bold mb-4">Informations de sante</h1>

            <?php if (! empty($errors ?? [])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('register/sante') ?>" class="vstack gap-3">
                <?= csrf_field() ?>
                <div>
                    <label class="form-label">Taille en cm</label>
                    <input type="number" step="0.01" name="taille" class="form-control" value="<?= esc($values['taille'] ?? '') ?>" required>
                </div>
                <div>
                    <label class="form-label">Poids en kg</label>
                    <input type="number" step="0.01" name="poids" class="form-control" value="<?= esc($values['poids'] ?? '') ?>" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Terminer l'inscription</button>
            </form>
        </div>
    </main>
</body>
</html>
