<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'administrateur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'email', 'mot_de_passe'];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
