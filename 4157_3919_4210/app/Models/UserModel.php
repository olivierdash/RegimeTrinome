<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users'; 
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = ['nom', 'email', 'mot_de_passe'];
    protected $useTimestamps = true;

    // Règles de validation centralisées
    protected $validationRules = [
        'nom'          => 'required|min_length[2]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'mot_de_passe' => 'required|min_length[8]',
    ];

    protected $beforeInsert = ['hashPassword'];

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
}