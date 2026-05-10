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
        return $this->suggestionsForUser($objectifId, [], $isGold);
    }

    public function suggestionsForUser(int $objectifId, array $user, bool $isGold): array
    {
        $builder = $this->select('regime.*, objectif.libelle objectif')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->orderBy('prix_journalier');

        if ($objectifId > 0) {
            $builder->where('regime.id_objectif', $objectifId);
        }

        $rows = $builder->findAll();
        $targetDelta = $this->targetDeltaForUser($objectifId, $user);

        foreach ($rows as &$row) {
            $daily = abs((float) ($row['poids_par_jour'] ?? 0));
            $delta = $targetDelta > 0 ? $targetDelta : 2.0;
            $row['duree_estimee'] = $daily > 0 ? max(7, (int) ceil($delta / $daily)) : 30;
            $row['prix_estime'] = $row['duree_estimee'] * (float) $row['prix_journalier'] * ($isGold ? 0.85 : 1);
            $row['sports'] = $this->sportSuggestions((int) $row['id']);
        }

        return $rows;
    }

    public function allWithObjective(): array
    {
        $rows = $this->select('regime.*, objectif.libelle objectif')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->orderBy('regime.id', 'DESC')
            ->findAll();

        foreach ($rows as &$row) {
            $row['sports'] = $this->sportSuggestions((int) $row['id']);
        }

        return $rows;
    }

    public function saveFromForm(array $data, int $id = 0): bool
    {
        if ($id > 0) {
            return $this->update($id, $data);
        }

        return (bool) $this->insert($data);
    }

    public function findWithObjective(int $id): ?array
    {
        return $this->select('regime.*, objectif.libelle objectif')
            ->join('objectif', 'objectif.id = regime.id_objectif')
            ->where('regime.id', $id)
            ->first();
    }

    public function sportSuggestions(int $regimeId): array
    {
        return $this->db->table('suggestion_sport')
            ->select('activite_sportive.*, suggestion_sport.duree_minutes_jour')
            ->join('activite_sportive', 'activite_sportive.id = suggestion_sport.id_activite')
            ->where('suggestion_sport.id_regime', $regimeId)
            ->orderBy('activite_sportive.designation')
            ->get()
            ->getResultArray();
    }

    public function replaceSportSuggestion(int $regimeId, int $activityId, int $duration): void
    {
        $this->db->table('suggestion_sport')->where('id_regime', $regimeId)->delete();

        if ($activityId <= 0 || $duration <= 0) {
            return;
        }

        $this->db->table('suggestion_sport')->insert([
            'id_regime' => $regimeId,
            'id_activite' => $activityId,
            'duree_minutes_jour' => $duration,
        ]);
    }

    private function targetDeltaForUser(int $objectifId, array $user): float
    {
        if (empty($user['taille']) || empty($user['poids'])) {
            return 2.0;
        }

        $height = (float) $user['taille'] / 100;
        if ($height <= 0) {
            return 2.0;
        }

        $ideal = 22 * $height * $height;
        $current = (float) $user['poids'];

        if ($objectifId === 1) {
            return max(2.0, $ideal - $current);
        }

        if ($objectifId === 2) {
            return max(2.0, $current - $ideal);
        }

        return max(1.0, abs($current - $ideal));
    }
}
