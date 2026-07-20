<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionSuppModel extends Model
{
    protected $table = 'commission_supp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['organisation_id', 'pourcentage'];
    protected $returnType = 'array';

    public function toutesAvecOrganisation()
    {
        return $this->db->table('commission_supp cs')
            ->select('cs.id, cs.organisation_id, cs.pourcentage, o.nom AS organisation')
            ->join('organisation o', 'o.id = cs.organisation_id')
            ->orderBy('o.nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function pourcentagePour($organisationId)
    {
        $ligne = $this->where('organisation_id', $organisationId)->first();
        return $ligne['pourcentage'] ?? 0;
    }
}
