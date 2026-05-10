<?php

namespace App\Models;

use CodeIgniter\Model;

class RechargeRequestModel extends Model
{
    protected $table = 'recharge_demande';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id_utilisateur', 'id_code', 'statut', 'date_validation'];

    public function codeAlreadyRequested(int $codeId): bool
    {
        return $this->where('id_code', $codeId)
            ->whereIn('statut', ['en_attente', 'valide'])
            ->countAllResults() > 0;
    }

    public function createPending(int $userId, int $codeId): bool
    {
        return (bool) $this->insert([
            'id_utilisateur' => $userId,
            'id_code' => $codeId,
            'statut' => 'en_attente',
        ]);
    }

    public function listForUser(int $userId): array
    {
        return $this->select('recharge_demande.*, code.numero_code, code.montant')
            ->join('code', 'code.id = recharge_demande.id_code')
            ->where('recharge_demande.id_utilisateur', $userId)
            ->orderBy('recharge_demande.id', 'DESC')
            ->findAll();
    }

    public function pendingWithDetails(): array
    {
        return $this->select('recharge_demande.*, utilisateur.nom utilisateur, utilisateur.email, code.numero_code, code.montant')
            ->join('utilisateur', 'utilisateur.id = recharge_demande.id_utilisateur')
            ->join('code', 'code.id = recharge_demande.id_code')
            ->where('recharge_demande.statut', 'en_attente')
            ->orderBy('recharge_demande.id', 'DESC')
            ->findAll();
    }

    public function findWithAmount(int $id): ?array
    {
        return $this->select('recharge_demande.*, code.montant, code.est_utilise')
            ->join('code', 'code.id = recharge_demande.id_code')
            ->where('recharge_demande.id', $id)
            ->first();
    }

    public function validateRecharge(int $id): bool
    {
        $row = $this->findWithAmount($id);
        if (! $row || $row['statut'] !== 'en_attente' || (int) $row['est_utilise'] === 1) {
            return false;
        }

        $this->db->transStart();
        $this->db->table('utilisateur')
            ->where('id', $row['id_utilisateur'])
            ->set('porte_monnaie', 'porte_monnaie + ' . (float) $row['montant'], false)
            ->update();
        $this->db->table('code')->where('id', $row['id_code'])->update(['est_utilise' => 1]);
        $this->update($id, ['statut' => 'valide', 'date_validation' => date('Y-m-d H:i:s')]);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function rejectPending(int $id): bool
    {
        return $this->where('id', $id)
            ->where('statut', 'en_attente')
            ->update(null, ['statut' => 'refuse', 'date_validation' => date('Y-m-d H:i:s')]);
    }

    public function countPending(): int
    {
        return $this->where('statut', 'en_attente')->countAllResults();
    }
}
