<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Codes</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Enregistrer un Code</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="alert alert-danger py-2">
                                <ul class="mb-0 small">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= site_url('admin/codes') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="code-id" value="<?= $values['id'] ?? '' ?>">

                            <div class="mb-3">
                                <label for="code-numero" class="form-label">Numéro du code</label>
                                <input type="text" 
                                       name="numero_code" 
                                       id="code-numero" 
                                       class="form-control <?= isset($errors['numero_code']) ? 'is-invalid' : '' ?>" 
                                       value="<?= $values['numero_code'] ?? '' ?>" 
                                       required>
                                <?php if (isset($errors['numero_code'])): ?>
                                    <div class="invalid-feedback"><?= $errors['numero_code'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="code-montant" class="form-label">Montant (Ar)</label>
                                <input type="number" 
                                       step="0.01" 
                                       name="montant" 
                                       id="code-montant" 
                                       class="form-control <?= isset($errors['montant']) ? 'is-invalid' : '' ?>" 
                                       value="<?= $values['montant'] ?? '' ?>" 
                                       required>
                                <?php if (isset($errors['montant'])): ?>
                                    <div class="invalid-feedback"><?= $errors['montant'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="code-utilise" class="form-label">État d'utilisation</label>
                                <select name="est_utilise" id="code-utilise" class="form-select">
                                    <option value="0" <?= (isset($values['est_utilise']) && $values['est_utilise'] == '0') ? 'selected' : '' ?>>Non utilisé</option>
                                    <option value="1" <?= (isset($values['est_utilise']) && $values['est_utilise'] == '1') ? 'selected' : '' ?>>Déjà utilisé</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Liste des Codes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Numéro</th>
                                        <th>Montant</th>
                                        <th>État</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($codes) && !empty($codes)): ?>
                                        <?php foreach ($codes as $code): ?>
                                            <tr>
                                                <td><?= $code['id'] ?></td>
                                                <td><strong><?= $code['numero_code'] ?></strong></td>
                                                <td><?= number_format($code['montant'], 2, ',', ' ') ?> Ar</td>
                                                <td>
                                                    <?php if ($code['est_utilise'] == 1): ?>
                                                        <span class="badge bg-danger">Utilisé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Disponible</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= site_url('admin/codes/edit/' . $code['id']) ?>" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                                    <a href="<?= site_url('admin/codes/delete/' . $code['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce code ?')">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Aucun code trouvé.</td>
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