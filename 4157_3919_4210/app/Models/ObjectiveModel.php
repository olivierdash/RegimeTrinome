<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectiveModel extends Model
{
    protected $table = 'objectif';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['libelle'];

    public function ordered(): array
    {
        return $this->orderBy('id')->findAll();
    }
}
