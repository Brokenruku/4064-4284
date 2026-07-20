<?php

namespace App\Models;

use CodeIgniter\Database\BaseConnection;

class CompteModel
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function liste()
    {
        return $this->db->table('v_compte_client')->orderBy('telephone', 'ASC')->get()->getResultArray();
    }

    public function resume($clientId)
    {
        return $this->db->table('v_resume_client')->where('client_id', $clientId)->get()->getRowArray();
    }

    public function historique($clientId)
    {
        return $this->db->table('v_historique_client')->where('client_id', $clientId)->get()->getResultArray();
    }
}