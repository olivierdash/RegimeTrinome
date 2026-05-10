<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/user_nav') ?>

    <main class="container py-4">
        <?= view('partials/flash') ?>

        <div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
            <div>
                <h1 class="h3 fw-bold mb-1">Bonjour, <?= esc($user['nom']) ?></h1>
                <p class="text-muted mb-0">Ton profil, ton budget et tes programmes au meme endroit.</p>
            </div>
            <a class="btn btn-primary" href="<?= site_url('dashboard/regimes') ?>">Voir les regimes</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="app-card metric-card">
                    <span>IMC</span>
                    <strong><?= number_format($imc, 1, ',', ' ') ?></strong>
                    <small><?= esc($imcStatus) ?></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="app-card metric-card">
                    <span>Poids ideal</span>
                    <strong><?= number_format($idealWeight, 1, ',', ' ') ?> kg</strong>
                    <small>Reference IMC 22</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="app-card metric-card">
                    <span>Solde</span>
                    <strong><?= number_format((float) $user['porte_monnaie'], 0, ',', ' ') ?> Ar</strong>
                    <small><?= (int) $user['est_gold'] === 1 ? 'Gold actif, remise 15%' : 'Compte standard' ?></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="app-card metric-card">
                    <span>Gold</span>
                    <strong><?= (int) $user['est_gold'] === 1 ? 'Actif' : number_format($goldPrice, 0, ',', ' ') . ' Ar' ?></strong>
                    <small>Remise sur tous les regimes</small>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="app-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">Derniers achats</h2>
                        <a href="<?= site_url('dashboard/suivi') ?>" class="btn btn-outline-secondary btn-sm">Tout voir</a>
                    </div>
                    <?php if (empty($achats)): ?>
                        <p class="text-muted mb-0">Aucun achat pour le moment.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <tbody>
                                <?php foreach (array_slice($achats, 0, 4) as $achat): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($achat['regime']) ?></strong><br>
                                            <span class="text-muted small"><?= esc($achat['duree_jours']) ?> jours</span>
                                        </td>
                                        <td class="text-end"><?= number_format((float) $achat['montant_total'], 0, ',', ' ') ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="app-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">Recharges</h2>
                        <a href="<?= site_url('dashboard/wallet') ?>" class="btn btn-outline-secondary btn-sm">Wallet</a>
                    </div>
                    <?php if (empty($recharges)): ?>
                        <p class="text-muted mb-0">Aucune recharge.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($recharges, 0, 4) as $recharge): ?>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>
                                    <strong><?= esc($recharge['numero_code']) ?></strong><br>
                                    <span class="text-muted small"><?= number_format((float) $recharge['montant'], 0, ',', ' ') ?> Ar</span>
                                </div>
                                <span class="badge text-bg-light align-self-center"><?= esc($recharge['statut']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
