<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeModel extends Model
{
    protected $table = 'code';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['numero_code', 'montant', 'est_utilise'];

    public function findAvailableByNumber(string $number): ?array
    {
        return $this->where('numero_code', $number)->where('est_utilise', 0)->first();
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
