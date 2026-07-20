<?php

namespace App\Models;

use CodeIgniter\Model;

class DepotModel extends Model
{
    protected $table = 'depot';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numero_telephone_id', 'montant', 'date_depot'];
    protected $returnType = 'array';
}