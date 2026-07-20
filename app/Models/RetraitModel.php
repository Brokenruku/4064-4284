<?php

namespace App\Models;

use CodeIgniter\Model;

class RetraitModel extends Model
{
    protected $table = 'retrait';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numero_telephone_id', 'montant', 'date_retrait'];
    protected $returnType = 'array';
}