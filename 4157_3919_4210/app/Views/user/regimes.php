<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Regimes') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/user_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="app-card mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1 class="h4 fw-bold">Regimes suggeres</h1>
                    <p class="text-muted mb-0">Selectionne un objectif, puis choisis une duree.</p>
                </div>
                <form method="get" action="<?= site_url('dashboard/regimes') ?>" class="d-flex gap-2">
                    <select name="objectif" class="form-select">
                        <option value="0">Tous les objectifs</option>
                        <?php foreach ($objectifs as $objectif): ?>
                            <option value="<?= $objectif['id'] ?>" <?= (int) $selectedObjective === (int) $objectif['id'] ? 'selected' : '' ?>>
                                <?= esc($objectif['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary" type="submit">Filtrer</button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($regimes as $regime): ?>
                <div class="col-lg-6">
                    <div class="app-card h-100">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <h2 class="h5 fw-bold mb-1"><?= esc($regime['nom']) ?></h2>
                                <span class="badge text-bg-light"><?= esc($regime['objectif']) ?></span>
                            </div>
                            <div class="text-end">
                                <strong><?= number_format((float) $regime['prix_journalier'], 0, ',', ' ') ?> Ar</strong><br>
                                <span class="text-muted small">par jour</span>
                            </div>
                        </div>

                        <div class="row g-2 my-3 small">
                            <div class="col-4"><span class="text-muted">Viande</span><br><strong><?= esc($regime['pourcentage_viande']) ?>%</strong></div>
                            <div class="col-4"><span class="text-muted">Poisson</span><br><strong><?= esc($regime['pourcentage_poisson']) ?>%</strong></div>
                            <div class="col-4"><span class="text-muted">Volaille</span><br><strong><?= esc($regime['pourcentage_volaille']) ?>%</strong></div>
                        </div>

                        <p class="mb-2">
                            Variation estimee:
                            <strong><?= ($regime['poids_par_jour'] > 0 ? '+' : '') . esc($regime['poids_par_jour']) ?> kg/jour</strong>
                        </p>
                        <p class="text-muted small mb-3">
                            Suggestion: <?= esc($regime['duree_estimee']) ?> jours,
                            <?= number_format((float) $regime['prix_estime'], 0, ',', ' ') ?> Ar
                            <?= (int) $user['est_gold'] === 1 ? 'avec remise Gold' : '' ?>
                        </p>

                        <?php if (! empty($regime['sports'])): ?>
                            <div class="border-top border-bottom py-2 mb-3 small">
                                <?php foreach ($regime['sports'] as $sport): ?>
                                    <div class="d-flex justify-content-between">
                                        <span><?= esc($sport['designation']) ?></span>
                                        <strong><?= esc($sport['duree_minutes_jour']) ?> min/j</strong>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= site_url('dashboard/regimes/acheter') ?>" class="row g-2 align-items-end">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_regime" value="<?= $regime['id'] ?>">
                            <div class="col">
                                <label class="form-label small">Duree en jours</label>
                                <input type="number" min="1" name="duree_jours" value="<?= esc($regime['duree_estimee']) ?>" class="form-control" required>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit">Acheter</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
