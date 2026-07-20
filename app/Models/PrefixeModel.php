<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['prefix', 'organisation_id'];
    protected $returnType = 'array';

    public function listeAvecOrganisation()
    {
        return $this->db->table('v_prefixes_organisation')->orderBy('organisation', 'ASC')->get()->getResultArray();
    }
}
