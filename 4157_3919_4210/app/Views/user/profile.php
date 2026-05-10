<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Profil') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/user_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="app-card metric-card">
                    <span>IMC actuel</span>
                    <strong><?= number_format($imc, 1, ',', ' ') ?></strong>
                    <small><?= esc($imcStatus) ?>, poids ideal <?= number_format($idealWeight, 1, ',', ' ') ?> kg</small>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="app-card">
                    <h1 class="h4 fw-bold mb-3">Completer ou modifier le profil</h1>
                    <?php if (! empty($errors ?? [])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?= site_url('dashboard/profil') ?>" class="row g-3">
                        <?= csrf_field() ?>
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input name="nom" class="form-control" value="<?= esc($values['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($values['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Genre</label>
                            <select name="genre" class="form-select" required>
                                <option value="Homme" <?= (($values['genre'] ?? '') === 'Homme') ? 'selected' : '' ?>>Homme</option>
                                <option value="Femme" <?= (($values['genre'] ?? '') === 'Femme') ? 'selected' : '' ?>>Femme</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Taille en cm</label>
                            <input type="number" step="0.01" name="taille" class="form-control" value="<?= esc($values['taille'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Poids en kg</label>
                            <input type="number" step="0.01" name="poids" class="form-control" value="<?= esc($values['poids'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="mot_de_passe" minlength="8" class="form-control">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
