<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regime';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_objectif',
        'nom',
        'prix_journalier',
        'poids_par_jour',
        'pourcentage_viande',
        'pourcentage_poisson',
        'pourcentage_volaille',
    ];

    public function suggestions(int $objectifId, bool $isGold): array
    {
        $builder = $this->select('regime.*, objectif.libelle objectif')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->orderBy('prix_journalier');

        if ($objectifId > 0) {
            $builder->where('regime.id_objectif', $objectifId);
        }

        $rows = $builder->findAll();
        foreach ($rows as &$row) {
            $daily = abs((float) $row['poids_par_jour']);
            $row['duree_estimee'] = $daily > 0 ? max(7, (int) ceil(2 / $daily)) : 30;
            $row['prix_estime'] = $row['duree_estimee'] * (float) $row['prix_journalier'] * ($isGold ? 0.85 : 1);
        }

        return $rows;
    }

    public function allWithObjective(): array
    {
        return $this->select('regime.*, objectif.libelle objectif')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->orderBy('regime.id', 'DESC')
            ->findAll();
    }

    public function saveFromForm(array $data, int $id = 0): bool
    {
        if ($id > 0) {
            return $this->update($id, $data);
        }

        return (bool) $this->insert($data);
    }
}
