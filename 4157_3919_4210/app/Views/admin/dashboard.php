<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Back-office') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/admin_nav') ?>

    <main class="container py-4">
        <?= view('partials/flash') ?>
        <h1 class="h3 fw-bold mb-4">Tableau de bord</h1>

        <div class="row g-3 mb-4">
            <div class="col-md-3"><div class="app-card metric-card"><span>Utilisateurs</span><strong><?= esc($stats['users']) ?></strong><small>Total inscrits</small></div></div>
            <div class="col-md-3"><div class="app-card metric-card"><span>Gold</span><strong><?= esc($stats['gold']) ?></strong><small>Comptes premium</small></div></div>
            <div class="col-md-3"><div class="app-card metric-card"><span>Chiffre</span><strong><?= number_format((float) $stats['sales'], 0, ',', ' ') ?> Ar</strong><small>Regimes vendus</small></div></div>
            <div class="col-md-3"><div class="app-card metric-card"><span>Recharges</span><strong><?= esc($stats['pending']) ?></strong><small>En attente</small></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="app-card mb-4">
                    <h2 class="h5 fw-bold mb-3">Ventes par objectif</h2>
                    <?php if (empty($byObjective)): ?>
                        <p class="text-muted mb-0">Aucune vente.</p>
                    <?php else: ?>
                        <?php $max = max(array_map(static fn ($row) => (float) $row['chiffre'], $byObjective)); ?>
                        <?php foreach ($byObjective as $row): ?>
                            <?php $width = $max > 0 ? ((float) $row['chiffre'] / $max) * 100 : 0; ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <strong><?= esc($row['libelle']) ?></strong>
                                    <span><?= number_format((float) $row['chiffre'], 0, ',', ' ') ?> Ar</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: <?= $width ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="app-card">
                    <h2 class="h5 fw-bold mb-3">Demandes de recharge</h2>
                    <?php if (empty($recharges)): ?>
                        <p class="text-muted mb-0">Aucune demande en attente.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Utilisateur</th><th>Code</th><th>Montant</th><th></th></tr></thead>
                                <tbody>
                                <?php foreach ($recharges as $recharge): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($recharge['utilisateur']) ?></strong><br>
                                            <span class="text-muted small"><?= esc($recharge['email']) ?></span>
                                        </td>
                                        <td><?= esc($recharge['numero_code']) ?></td>
                                        <td><?= number_format((float) $recharge['montant'], 0, ',', ' ') ?> Ar</td>
                                        <td class="text-end">
                                            <form method="post" action="<?= site_url('admin/recharges/valider/' . $recharge['id']) ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-success btn-sm">Valider</button>
                                            </form>
                                            <form method="post" action="<?= site_url('admin/recharges/refuser/' . $recharge['id']) ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-outline-danger btn-sm">Refuser</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="app-card mb-4">
                    <h2 class="h5 fw-bold mb-3">Top regimes</h2>
                    <?php if (empty($byRegime)): ?>
                        <p class="text-muted mb-0">Aucune vente.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Regime</th><th>Achats</th><th>Chiffre</th></tr></thead>
                                <tbody>
                                <?php foreach ($byRegime as $row): ?>
                                    <tr>
                                        <td><strong><?= esc($row['regime']) ?></strong><br><span class="text-muted small"><?= esc($row['objectif']) ?></span></td>
                                        <td><?= esc($row['achats']) ?></td>
                                        <td><?= number_format((float) $row['chiffre'], 0, ',', ' ') ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="app-card">
                    <h2 class="h5 fw-bold mb-3">Evolution recente</h2>
                    <?php if (empty($byDate)): ?>
                        <p class="text-muted mb-0">Aucune vente.</p>
                    <?php else: ?>
                        <?php foreach ($byDate as $row): ?>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span><?= esc($row['date']) ?></span>
                                <strong><?= number_format((float) $row['chiffre'], 0, ',', ' ') ?> Ar</strong>
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
