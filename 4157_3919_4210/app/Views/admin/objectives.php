<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Objectifs') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/admin_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="app-card mb-4">
            <h1 class="h4 fw-bold mb-3">CRUD des objectifs</h1>
            <form method="post" action="<?= site_url('admin/objectifs') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="objective-id">
                <div class="col-md-9">
                    <label class="form-label">Libelle</label>
                    <input name="libelle" id="objective-libelle" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <button class="btn btn-outline-secondary" type="reset" onclick="document.getElementById('objective-id').value=''">Nouveau</button>
                </div>
            </form>
        </div>

        <div class="app-card">
            <h2 class="h5 fw-bold mb-3">Objectifs en base</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Libelle</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($objectifs as $objectif): ?>
                        <tr>
                            <td><strong><?= esc($objectif['libelle']) ?></strong></td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick='fillObjective(<?= json_encode($objectif) ?>)'>Modifier</button>
                                <form method="post" action="<?= site_url('admin/objectifs/delete/' . $objectif['id']) ?>" class="d-inline" onsubmit="return confirm('Supprimer cet objectif ?');">
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
    function fillObjective(objective) {
        document.getElementById('objective-id').value = objective.id;
        document.getElementById('objective-libelle').value = objective.libelle;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
