<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Suivi') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/user_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="app-card mb-4">
            <h1 class="h4 fw-bold mb-3">Historique des achats</h1>
            <?php if (empty($achats)): ?>
                <p class="text-muted mb-0">Aucun achat.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Regime</th><th>Duree</th><th>Montant</th><th>Date</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($achats as $achat): ?>
                            <tr>
                                <td><strong><?= esc($achat['regime']) ?></strong></td>
                                <td><?= esc($achat['duree_jours']) ?> jours</td>
                                <td><?= number_format((float) $achat['montant_total'], 0, ',', ' ') ?> Ar</td>
                                <td><?= esc($achat['date_debut']) ?></td>
                                <td class="text-end"><a class="btn btn-outline-primary btn-sm" href="<?= site_url('dashboard/export/' . $achat['id']) ?>">PDF</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="app-card">
            <h2 class="h5 fw-bold mb-3">Demandes de recharge</h2>
            <?php if (empty($recharges)): ?>
                <p class="text-muted mb-0">Aucune demande.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Code</th><th>Montant</th><th>Statut</th><th>Validation</th></tr></thead>
                        <tbody>
                        <?php foreach ($recharges as $recharge): ?>
                            <tr>
                                <td><?= esc($recharge['numero_code']) ?></td>
                                <td><?= number_format((float) $recharge['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><span class="badge text-bg-light"><?= esc($recharge['statut']) ?></span></td>
                                <td><?= esc($recharge['date_validation'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
