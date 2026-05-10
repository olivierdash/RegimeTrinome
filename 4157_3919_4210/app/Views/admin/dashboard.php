<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">

</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Liste des régimes</h5>
                            <small class="text-muted"><?= count($regimes) ?> régime(s) enregistré(s)</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">Nom</th>
                                    <th>Objectif</th>
                                    <th>Prix/Jour</th>
                                    <th>Variation (Kg)</th>
                                    <th>Composition (%)</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($regimes) && !empty($regimes)): ?>
                                    <?php foreach ($regimes as $r): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><?= esc($r['nom']) ?></td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                                    <?= esc($r['objectif']) ?>
                                                </span>
                                            </td>
                                            <td class="fw-semibold">
                                                <?= number_format($r['prix_journalier'], 0, ',', ' ') ?> Ar
                                            </td>
                                            <td><?= esc($r['poids_par_jour']) ?> kg</td>
                                            <td>
                                                <div class="d-flex gap-1 small">
                                                    <span class="badge bg-light text-dark border">🍖 <?= esc($r['pourcentage_viande']) ?>%</span>
                                                    <span class="badge bg-light text-dark border">🐟 <?= esc($r['pourcentage_poisson']) ?>%</span>
                                                    <span class="badge bg-light text-dark border">🍗 <?= esc($r['pourcentage_volaille']) ?>%</span>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" onclick='fillRegime(<?= json_encode($r) ?>)'>
                                                        Modifier
                                                    </button>
                                                    <form method="post" action="<?= site_url('admin/regimes/delete/' . $r['id']) ?>" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-outline-danger rounded-3">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            Aucun régime disponible.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>
</html>