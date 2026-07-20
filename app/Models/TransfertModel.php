<?php

namespace App\Models;

use CodeIgniter\Model;

class TransfertModel extends Model
{
    protected $table = 'transfert';
    protected $primaryKey = 'id';
    protected $allowedFields = ['expediteur_id', 'destinataire_id', 'montant', 'date_transfert'];
    protected $returnType = 'array';
}