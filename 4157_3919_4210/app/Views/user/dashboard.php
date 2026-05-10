<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= site_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>

<body>

    <div class="profile-header text-center">
        <div class="container">
            <div class="avatar-circle">
                <?= strtoupper(substr(esc($user['nom']), 0, 1)) ?>
            </div>
            <h1 class="fw-bold h2 mb-1">Bonjour, <?= esc($user['nom']) ?></h1>
            <p class="text-muted">Prêt pour vos objectifs d'aujourd'hui ?</p>
        </div>
    </div>

    <div class="container pb-5">
        <!-- Métriques principales en Cartes -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3 text-center">
                    <span class="metric-label mb-2 d-block">Indice de Masse Corporelle</span>
                    <div class="metric-value"><?= number_format($imc, 1, ',', ' ') ?></div>
                    <span class="badge bg-light text-dark align-self-center mt-2 px-3"><?= esc($imcStatus) ?></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3 text-center border-primary border-top border-4">
                    <span class="metric-label mb-2 d-block">Solde Actuel</span>
                    <div class="metric-value text-primary"><?= number_format($user['porte_monnaie'], 0, ',', ' ') ?> <small>Ar</small></div>
                    <span class="text-muted small mt-2"><?= $user['est_gold'] ? '✨ Compte Gold actif (-15%)' : 'Compte Standard' ?></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3 text-center">
                    <span class="metric-label mb-2 d-block">Poids Idéal</span>
                    <div class="metric-value"><?= number_format($idealWeight, 1, ',', ' ') ?> <small>kg</small></div>
                    <span class="text-muted small mt-2">Basé sur votre morphologie</span>
                </div>
            </div>
        </div>

        <!-- Navigation rapide (Espacement blanc généreux) -->
        <div class="row g-4 mb-5 text-center">
            <?php
            $menu = [
                ['Profil', 'person', 'profil', 'Gérer ma santé'],
                ['Régimes', 'apple', 'regimes', 'Trouver un programme'],
                ['Wallet', 'wallet2', 'wallet', 'Recharger mon compte'],
                ['Suivi', 'graph-up', 'suivi', 'Historique d\'activités']
            ];
            foreach ($menu as $item):
            ?>
                <div class="col-6 col-lg-3">
                    <a href="<?= site_url('dashboard/' . $item[2]) ?>" class="text-decoration-none p-4 d-block bg-white rounded-4 shadow-sm border border-light">
                        <div class="h3 text-primary mb-2"><i class="bi bi-<?= $item[1] ?>"></i></div>
                        <h6 class="text-dark fw-bold mb-1"><?= $item[0] ?></h6>
                        <small class="text-muted d-none d-md-block"><?= $item[3] ?></small>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Détails et Activités -->
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Derniers achats</h5>
                        <a href="<?= site_url('dashboard/suivi') ?>" class="btn btn-sm btn-light text-primary fw-bold">Voir tout</a>
                    </div>
                    <?php if (empty($achats)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun achat récent.</p>
                        </div>
                    <?php else: ?>
                        <table class="table table-clean mb-0">
                            <thead>
                                <tr>
                                    <th>RÉGIME</th>
                                    <th class="text-end">MONTANT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($achats, 0, 3) as $achat): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold d-block text-dark"><?= esc($achat['regime']) ?></span>
                                            <small class="text-muted"><?= esc($achat['duree_jours']) ?> jours de suivi</small>
                                        </td>
                                        <td class="text-end fw-bold"><?= number_format($achat['montant_total'], 0, ',', ' ') ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card stat-card p-4">
                    <h5 class="fw-bold mb-4">Recharges récentes</h5>
                    <?php if (empty($recharges)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Aucune recharge.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($recharges, 0, 3) as $recharge): ?>
                                <div class="list-group-item px-0 border-0 mb-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <code class="text-primary fw-bold"><?= esc($recharge['numero_code']) ?></code>
                                        <div class="small text-muted"><?= number_format($recharge['montant'], 0, ',', ' ') ?> Ar</div>
                                    </div>
                                    <span class="status-badge bg-<?= ($recharge['statut'] == 'valide') ? 'success-subtle text-success' : 'warning-subtle text-warning' ?>">
                                        <?= strtoupper(esc($recharge['statut'])) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>