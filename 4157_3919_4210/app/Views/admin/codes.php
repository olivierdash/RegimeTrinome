<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Codes') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/admin_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="app-card mb-4">
            <h1 class="h4 fw-bold mb-3">CRUD des codes porte-monnaie</h1>
            <form method="post" action="<?= site_url('admin/codes') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="code-id">
                <div class="col-md-5">
                    <label class="form-label">Numero du code</label>
                    <input name="numero_code" id="code-numero" class="form-control text-uppercase" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" min="0" name="montant" id="code-montant" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Etat</label>
                    <select name="est_utilise" id="code-utilise" class="form-select">
                        <option value="0">Disponible</option>
                        <option value="1">Utilise</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <button class="btn btn-outline-secondary" type="reset" onclick="document.getElementById('code-id').value=''">Nouveau</button>
                </div>
            </form>
        </div>

        <div class="app-card">
            <h2 class="h5 fw-bold mb-3">Codes en base</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Code</th><th>Montant</th><th>Etat</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($codes as $code): ?>
                        <tr>
                            <td><strong><?= esc($code['numero_code']) ?></strong></td>
                            <td><?= number_format((float) $code['montant'], 0, ',', ' ') ?> Ar</td>
                            <td><span class="badge <?= (int) $code['est_utilise'] === 1 ? 'text-bg-secondary' : 'text-bg-success' ?>"><?= (int) $code['est_utilise'] === 1 ? 'Utilise' : 'Disponible' ?></span></td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm" onclick='fillCode(<?= json_encode($code) ?>)'>Modifier</button>
                                <form method="post" action="<?= site_url('admin/codes/delete/' . $code['id']) ?>" class="d-inline" onsubmit="return confirm('Supprimer ce code ?');">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
    function fillCode(code) {
        document.getElementById('code-id').value = code.id;
        document.getElementById('code-numero').value = code.numero_code;
        document.getElementById('code-montant').value = code.montant;
        document.getElementById('code-utilise').value = code.est_utilise;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
