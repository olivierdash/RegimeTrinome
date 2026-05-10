<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table = 'achat_regime';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id_utilisateur', 'id_regime', 'date_debut', 'duree_jours', 'montant_total'];

    public function listForUser(int $userId): array
    {
        return $this->select('achat_regime.*, regime.nom regime')
            ->join('regime', 'regime.id = achat_regime.id_regime')
            ->where('achat_regime.id_utilisateur', $userId)
            ->orderBy('achat_regime.id', 'DESC')
            ->findAll();
    }

    public function findForExport(int $purchaseId, int $userId): ?array
    {
        return $this->select('achat_regime.*, regime.nom regime, regime.poids_par_jour, regime.pourcentage_viande, regime.pourcentage_poisson, regime.pourcentage_volaille')
            ->join('regime', 'regime.id = achat_regime.id_regime')
            ->where('achat_regime.id', $purchaseId)
            ->where('achat_regime.id_utilisateur', $userId)
            ->first();
    }

    public function createWithWalletDebit(array $user, array $regime, int $duration, float $total): bool
    {
        $this->db->transStart();
        $this->db->table('utilisateur')->where('id', $user['id'])->update([
            'porte_monnaie' => ((float) $user['porte_monnaie']) - $total,
        ]);
        $this->insert([
            'id_utilisateur' => $user['id'],
            'id_regime' => $regime['id'],
            'date_debut' => date('Y-m-d'),
            'duree_jours' => $duration,
            'montant_total' => $total,
        ]);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function totalSales(): float
    {
        $row = $this->selectSum('montant_total')->first();

        return (float) ($row['montant_total'] ?? 0);
    }

    public function salesByObjective(): array
    {
        return $this->select('objectif.libelle, COUNT(*) total, SUM(achat_regime.montant_total) chiffre')
            ->join('regime', 'regime.id = achat_regime.id_regime')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->groupBy('objectif.id, objectif.libelle')
            ->findAll();
    }

    public function salesByRegime(): array
    {
        return $this->select('regime.nom regime, objectif.libelle objectif, COUNT(achat_regime.id) achats, SUM(achat_regime.duree_jours) jours, SUM(achat_regime.montant_total) chiffre, AVG(achat_regime.montant_total) panier_moyen')
            ->join('regime', 'regime.id = achat_regime.id_regime')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->groupBy('regime.id, regime.nom, objectif.libelle')
            ->orderBy('chiffre', 'DESC')
            ->findAll();
    }

    public function recentSalesByDate(int $limit = 12): array
    {
        $rows = $this->select('achat_regime.date_debut date, COUNT(achat_regime.id) achats, SUM(achat_regime.duree_jours) jours, SUM(achat_regime.montant_total) chiffre, AVG(achat_regime.montant_total) panier_moyen')
            ->groupBy('achat_regime.date_debut')
            ->orderBy('achat_regime.date_debut', 'DESC')
            ->findAll($limit);

        return array_reverse($rows);
    }
}
