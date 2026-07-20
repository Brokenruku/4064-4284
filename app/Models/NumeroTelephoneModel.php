<?php

namespace App\Models;

use CodeIgniter\Model;

class NumeroTelephoneModel extends Model
{
    protected $table = 'numero_telephone';
    protected $primaryKey = 'id';
    protected $allowedFields = ['prefix_id', 'numero', 'solde'];
    protected $returnType = 'array';

    public function listeAvecPrefixe()
    {
        return $this->db->table('v_compte_client')->orderBy('telephone', 'ASC')->get()->getResultArray();
    }

    public function ajusterSolde($id, $montant)
    {
        $client = $this->find($id);
        if (! $client) {
            return false;
        }
        $nouveauSolde = $client['solde'] + $montant;
        return $this->update($id, ['solde' => $nouveauSolde]);
    }
}