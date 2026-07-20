<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisTransfertModel extends Model
{
    protected $table = 'frais_transfert';
    protected $primaryKey = 'id';
    protected $allowedFields = ['montant_min', 'montant_max', 'frais'];
    protected $returnType = 'array';

    public function calculerFrais($montant)
    {
        $tranche = $this->where('montant_min <=', $montant)->where('montant_max >=', $montant)->first();
        return $tranche['frais'] ?? 0;
    }

    public function toutesTranches()
    {
        return $this->orderBy('montant_min', 'ASC')->findAll();
    }
}