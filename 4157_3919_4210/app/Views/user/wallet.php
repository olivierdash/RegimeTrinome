<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Wallet') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/user_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="app-card metric-card mb-4">
                    <span>Solde disponible</span>
                    <strong><?= number_format((float) $user['porte_monnaie'], 0, ',', ' ') ?> Ar</strong>
                    <small><?= (int) $user['est_gold'] === 1 ? 'Compte Gold actif' : 'Compte standard' ?></small>
                </div>
                <div class="app-card">
                    <h1 class="h5 fw-bold mb-3">Recharger par code</h1>
                    <form method="post" action="<?= site_url('dashboard/wallet/recharge') ?>" class="vstack gap-3">
                        <?= csrf_field() ?>
                        <input name="numero_code" class="form-control text-uppercase" placeholder="CODE01" required>
                        <button class="btn btn-primary" type="submit">Envoyer la demande</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="app-card mb-4">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <h2 class="h5 fw-bold mb-1">Option Gold</h2>
                            <p class="text-muted mb-0">Paiement unique: <?= number_format($goldPrice, 0, ',', ' ') ?> Ar. Remise de 15% sur tous les regimes.</p>
                        </div>
                        <?php if ((int) $user['est_gold'] === 1): ?>
                            <span class="badge text-bg-success">Actif</span>
                        <?php else: ?>
                            <form method="post" action="<?= site_url('dashboard/wallet/gold') ?>">
                                <?= csrf_field() ?>
                                <button class="btn btn-warning" type="submit">Activer Gold</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="app-card">
                    <h2 class="h5 fw-bold mb-3">Historique des recharges</h2>
                    <?php if (empty($recharges)): ?>
                        <p class="text-muted mb-0">Aucune demande.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Code</th><th>Montant</th><th>Statut</th><th>Date</th></tr></thead>
                                <tbody>
                                <?php foreach ($recharges as $recharge): ?>
                                    <tr>
                                        <td><strong><?= esc($recharge['numero_code']) ?></strong></td>
                                        <td><?= number_format((float) $recharge['montant'], 0, ',', ' ') ?> Ar</td>
                                        <td><span class="badge text-bg-light"><?= esc($recharge['statut']) ?></span></td>
                                        <td><?= esc($recharge['date_demande']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
