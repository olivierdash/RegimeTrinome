<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'activite_sportive';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['designation', 'calories_moyennes_heure'];

    public function orderedByName(): array
    {
        return $this->orderBy('designation')->findAll();
    }

    public function orderedByNewest(): array
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    public function saveFromForm(array $data, int $id = 0): bool
    {
        if ($id > 0) {
            return $this->update($id, $data);
        }

        return (bool) $this->insert($data);
    }
}
