<div class="container py-5">
    <!-- En-tête de page -->
    <div class="mb-5">
        <span class="text-primary fw-bold text-uppercase small tracking-wider">Back-office</span>
        <h1 class="fw-bold display-6">Gestion des Régimes</h1>
        <p class="text-muted">Configurez les programmes nutritionnels et leurs compositions.</p>
    </div>

    <!-- Section Formulaire -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary-subtle p-2 rounded-3 me-3">
                    <i class="bi bi-plus-circle text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="card-title fw-bold mb-0">Édition du régime</h5>
                    <p class="small text-muted mb-0">Remplissez les informations essentielles et la répartition nutritionnelle.</p>
                </div>
            </div>

            <form method="post" action="<?= site_url('admin/regimes') ?>" class="row g-3">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="regime-id">

                <!-- Informations de base -->
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Nom du régime</label>
                    <input name="nom" id="regime-nom" class="form-control rounded-3" placeholder="Ex: Keto Force" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Objectif</label>
                    <select name="id_objectif" id="regime-objectif" class="form-select rounded-3">
                        <?php foreach ($objectifs as $o): ?>
                            <option value="<?= $o['id'] ?>"><?= esc($o['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Prix / Jour (Ar)</label>
                    <input type="number" step="0.01" name="prix_journalier" id="regime-prix" class="form-control rounded-3" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Variation (Kg/j)</label>
                    <input type="number" step="0.001" name="poids_par_jour" id="regime-poids" class="form-control rounded-3" required>
                </div>

                <!-- Composition (Groupée) -->
                <div class="col-12 mt-4">
                    <div class="p-3 bg-light rounded-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <span class="d-block small fw-bold mb-2 text-dark"><i class="bi bi-pie-chart-fill me-2"></i>Composition du régime</span>
                                <p class="x-small text-muted mb-0">La somme doit idéalement égaler 100%.</p>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">🍖</span>
                                    <input type="number" step="0.01" name="pourcentage_viande" id="regime-viande" class="form-control border-start-0" placeholder="% Viande" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">🐟</span>
                                    <input type="number" step="0.01" name="pourcentage_poisson" id="regime-poisson" class="form-control border-start-0" placeholder="% Poisson" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">🍗</span>
                                    <input type="number" step="0.01" name="pourcentage_volaille" id="regime-volaille" class="form-control border-start-0" placeholder="% Volaille" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <button type="reset" class="btn btn-light rounded-pill px-4 me-2">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Enregistrer le régime</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste (Tableau) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">Régimes enregistrés</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted">
                        <th class="ps-4">NOM</th>
                        <th>OBJECTIF</th>
                        <th>PRIX/J</th>
                        <th>VARIATION</th>
                        <th>COMPOSITION (V/P/Vo)</th>
                        <th class="text-end pe-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regimes as $r): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= esc($r['nom']) ?></td>
                            <td><span class="badge bg-info-subtle text-info rounded-pill px-3"><?= esc($r['objectif']) ?></span></td>
                            <td class="fw-semibold text-dark"><?= number_format($r['prix_journalier'], 0, ',', ' ') ?> Ar</td>
                            <td><span class="text-<?= ($r['poids_par_jour'] < 0) ? 'danger' : 'success' ?> fw-bold"><?= ($r['poids_par_jour'] > 0 ? '+' : '') . esc($r['poids_par_jour']) ?> kg</span></td>
                            <td>
                                <div class="progress" style="height: 8px; width: 120px;">
                                    <div class="progress-bar bg-danger" style="width: <?= $r['pourcentage_viande'] ?>%"></div>
                                    <div class="progress-bar bg-primary" style="width: <?= $r['pourcentage_poisson'] ?>%"></div>
                                    <div class="progress-bar bg-warning" style="width: <?= $r['pourcentage_volaille'] ?>%"></div>
                                </div>
                                <small class="text-muted x-small"><?= $r['pourcentage_viande'] ?>/<?= $r['pourcentage_poisson'] ?>/<?= $r['pourcentage_volaille'] ?>%</small>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm rounded-3">
                                    <button class="btn btn-sm btn-white border" onclick='fillRegime(<?= json_encode($r) ?>)'>Modifier</button>
                                    <form method="post" action="<?= site_url('admin/regimes/delete/' . $r['id']) ?>" class="d-inline" onsubmit="return confirm('Supprimer ce régime ?');">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-white border text-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.75rem; }
    .tracking-wider { letter-spacing: 0.1em; }
    .btn-white:hover { background: #f8f9fa; }
</style>

<script>
function fillRegime(r){
    document.getElementById('regime-id').value=r.id;
    document.getElementById('regime-nom').value=r.nom;
    document.getElementById('regime-objectif').value=r.id_objectif;
    document.getElementById('regime-prix').value=r.prix_journalier;
    document.getElementById('regime-poids').value=r.poids_par_jour;
    document.getElementById('regime-viande').value=r.pourcentage_viande;
    document.getElementById('regime-poisson').value=r.pourcentage_poisson;
    document.getElementById('regime-volaille').value=r.pourcentage_volaille;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>