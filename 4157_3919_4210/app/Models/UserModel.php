<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'utilisateur';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'nom',
        'email',
        'genre',
        'mot_de_passe',
        'taille',
        'poids',
        'porte_monnaie',
        'est_gold',
    ];
    protected $useTimestamps = false;

    // Règles de validation centralisées
    protected $validationRules = [
        'nom'          => 'required|min_length[2]',
        'email'        => 'required|valid_email|is_unique[utilisateur.email]',
        'mot_de_passe' => 'required|min_length[8]',
    ];

    protected $beforeInsert = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['mot_de_passe'])) {
            $data['data']['mot_de_passe'] = password_hash($data['data']['mot_de_passe'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // Messages d'erreurs personnalisés
    protected $validationMessages = [
        'nom' => [
            'required' => 'Veuillez donner une valeur non vide au nom.',
        ],
        'email' => [
            'required'    => 'L\'email est obligatoire.',
            'valid_email' => 'Veuillez donner une valeur valide au format d\'un email.',
            'is_unique'   => 'Cet email est déjà utilisé.'
        ],
        'mot_de_passe' => [
            'required'   => 'Veuillez entrer un mot de passe.',
            'min_length' => 'Le mot de passe doit contenir au moins 6 caractères.'
        ],
    ];

    protected $skipValidation = false;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function emailExists(string $email, int $exceptId = 0): bool
    {
        $builder = $this->where('email', $email);
        if ($exceptId > 0) {
            $builder->where('id !=', $exceptId);
        }

        return $builder->countAllResults() > 0;
    }

    public function createPendingRegistration(array $data): int
    {
        $this->skipValidation(true);
        $id = (int) $this->insert($data);
        $this->skipValidation(false);

        return $id;
    }

    public function completeHealthProfile(int $userId, float $taille, float $poids): bool
    {
        return $this->update($userId, [
            'taille' => $taille,
            'poids' => $poids,
        ]);
    }

    public function updateProfile(int $userId, array $data): bool
    {
        $this->skipValidation(true);
        $updated = $this->update($userId, $data);
        $this->skipValidation(false);

        return $updated;
    }

    public function countGoldUsers(): int
    {
        return $this->where('est_gold', 1)->countAllResults();
    }

    public function activateGold(array $user, float $price): bool
    {
        if ((int) $user['est_gold'] === 1 || (float) $user['porte_monnaie'] < $price) {
            return false;
        }

        return $this->update((int) $user['id'], [
            'porte_monnaie' => (float) $user['porte_monnaie'] - $price,
            'est_gold' => 1,
        ]);
    }
}
