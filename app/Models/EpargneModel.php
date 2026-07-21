<?php

namespace App\Models;

use CodeIgniter\Model;

class Epargne extends Model
{
    protected $table = 'epargne' , ;
    protected $primaryKey = 'id';
    protected $allowedFields = ['orgqnisation_id', 'prefixes','pourcentage', 'montant'];
    protected $returnType = 'array';
*
}
