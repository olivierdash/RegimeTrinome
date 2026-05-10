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
                            <h5 class="fw-bold mb-0 text-dark">Liste des codes porte-monnaie</h5>
                            <small class="text-muted"><?= count($codes) ?> code(s) en base</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">Code / Numéro</th>
                                    <th>Montant</th>
                                    <th>État</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($codes) && !empty($codes)): ?>
                                    <?php foreach ($codes as $c): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <code class="text-primary fw-bold fs-6"><?= esc($c['numero_code']) ?></code>
                                            </td>
                                            <td class="fw-semibold">
                                                <?= number_format($c['montant'], 0, ',', ' ') ?> Ar
                                            </td>
                                            <td>
                                                <?php if ($c['est_utilise']): ?>
                                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                        Utilisé
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                        Disponible
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" onclick='fillCode(<?= json_encode($c) ?>)'>
                                                        Modifier
                                                    </button>
                                                    <form method="post" action="<?= site_url('admin/codes/delete/' . $c['id']) ?>" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Supprimer ce code ?')">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            Aucun code de recharge trouvé.
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