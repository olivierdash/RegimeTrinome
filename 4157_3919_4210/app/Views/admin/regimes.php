<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Regimes') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <?= view('partials/admin_nav') ?>
    <main class="container py-4">
        <?= view('partials/flash') ?>
        <div class="app-card mb-4">
            <h1 class="h4 fw-bold mb-3">CRUD des regimes</h1>
            <form method="post" action="<?= site_url('admin/regimes') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="regime-id">
                <div class="col-md-4">
                    <label class="form-label">Nom</label>
                    <input name="nom" id="regime-nom" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Objectif</label>
                    <select name="id_objectif" id="regime-objectif" class="form-select" required>
                        <?php foreach ($objectifs as $objectif): ?>
                            <option value="<?= $objectif['id'] ?>"><?= esc($objectif['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Prix/jour</label>
                    <input type="number" step="0.01" min="0" name="prix_journalier" id="regime-prix" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kg/jour</label>
                    <input type="number" step="0.001" name="poids_par_jour" id="regime-poids" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Viande %</label>
                    <input type="number" step="0.01" min="0" max="100" name="pourcentage_viande" id="regime-viande" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Poisson %</label>
                    <input type="number" step="0.01" min="0" max="100" name="pourcentage_poisson" id="regime-poisson" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Volaille %</label>
                    <input type="number" step="0.01" min="0" max="100" name="pourcentage_volaille" id="regime-volaille" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Activite conseillee</label>
                    <select name="id_activite" id="regime-activite" class="form-select">
                        <option value="0">Aucune</option>
                        <?php foreach ($activities as $activity): ?>
                            <option value="<?= $activity['id'] ?>"><?= esc($activity['designation']) ?> (<?= esc($activity['calories_moyennes_heure']) ?> cal/h)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Minutes/jour</label>
                    <input type="number" min="0" name="duree_minutes_jour" id="regime-duree-sport" class="form-control" value="0">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                    <button class="btn btn-outline-secondary" type="reset" onclick="resetRegimeForm()">Nouveau</button>
                </div>
            </form>
        </div>

        <div class="app-card">
            <h2 class="h5 fw-bold mb-3">Regimes en base</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Nom</th><th>Objectif</th><th>Prix</th><th>Variation</th><th>Composition</th><th>Sport</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($regimes as $regime): ?>
                        <tr>
                            <td><strong><?= esc($regime['nom']) ?></strong></td>
                            <td><?= esc($regime['objectif']) ?></td>
                            <td><?= number_format((float) $regime['prix_journalier'], 0, ',', ' ') ?> Ar</td>
                            <td><?= ($regime['poids_par_jour'] > 0 ? '+' : '') . esc($regime['poids_par_jour']) ?> kg/j</td>
                            <td><?= esc($regime['pourcentage_viande']) ?>/<?= esc($regime['pourcentage_poisson']) ?>/<?= esc($regime['pourcentage_volaille']) ?>%</td>
                            <td>
                                <?php if (empty($regime['sports'])): ?>
                                    <span class="text-muted">-</span>
                                <?php else: ?>
                                    <?php foreach ($regime['sports'] as $sport): ?>
                                        <div><?= esc($sport['designation']) ?>, <?= esc($sport['duree_minutes_jour']) ?> min/j</div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick='fillRegime(<?= json_encode($regime) ?>)'>Modifier</button>
                                <form method="post" action="<?= site_url('admin/regimes/delete/' . $regime['id']) ?>" class="d-inline" onsubmit="return confirm('Supprimer ce regime ?');">
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
    function fillRegime(regime) {
        document.getElementById('regime-id').value = regime.id;
        document.getElementById('regime-nom').value = regime.nom;
        document.getElementById('regime-objectif').value = regime.id_objectif;
        document.getElementById('regime-prix').value = regime.prix_journalier;
        document.getElementById('regime-poids').value = regime.poids_par_jour;
        document.getElementById('regime-viande').value = regime.pourcentage_viande;
        document.getElementById('regime-poisson').value = regime.pourcentage_poisson;
        document.getElementById('regime-volaille').value = regime.pourcentage_volaille;
        const sport = (regime.sports && regime.sports.length) ? regime.sports[0] : null;
        document.getElementById('regime-activite').value = sport ? sport.id : 0;
        document.getElementById('regime-duree-sport').value = sport ? sport.duree_minutes_jour : 0;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function resetRegimeForm() {
        document.getElementById('regime-id').value = '';
    }
    </script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
