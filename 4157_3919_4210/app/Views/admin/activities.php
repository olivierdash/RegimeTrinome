<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Activites') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/admin_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="app-card mb-4">
            <h1 class="h4 fw-bold mb-3">CRUD des activites sportives</h1>
            <form method="post" action="<?= site_url('admin/activites') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="activity-id">
                <div class="col-md-8">
                    <label class="form-label">Designation</label>
                    <input name="designation" id="activity-designation" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Calories moyennes / heure</label>
                    <input type="number" min="1" name="calories_moyennes_heure" id="activity-calories" class="form-control" required>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <button class="btn btn-outline-secondary" type="reset" onclick="document.getElementById('activity-id').value=''">Nouveau</button>
                </div>
            </form>
        </div>

        <div class="app-card">
            <h2 class="h5 fw-bold mb-3">Activites en base</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Designation</th><th>Calories</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><strong><?= esc($activity['designation']) ?></strong></td>
                            <td><?= esc($activity['calories_moyennes_heure']) ?> cal/h</td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm" onclick='fillActivity(<?= json_encode($activity) ?>)'>Modifier</button>
                                <form method="post" action="<?= site_url('admin/activites/delete/' . $activity['id']) ?>" class="d-inline" onsubmit="return confirm('Supprimer cette activite ?');">
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
    function fillActivity(activity) {
        document.getElementById('activity-id').value = activity.id;
        document.getElementById('activity-designation').value = activity.designation;
        document.getElementById('activity-calories').value = activity.calories_moyennes_heure;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
