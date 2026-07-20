<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganisationModel extends Model
{
    protected $table = 'organisation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom'];
    protected $returnType = 'array';
}
