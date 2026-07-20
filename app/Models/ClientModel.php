<?php

namespace App\Models;

use CodeIgniter\Database\BaseConnection;

class ClientModel
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function trouverParPrefixEtNumero($prefixId, $numero)
    {
        return $this->db->table('v_login_rapide')
            ->where('prefix_id', $prefixId)
            ->where('numero', $numero)
            ->get()
            ->getRowArray();
    }

    public function trouverParTelephone($telephone)
    {
        return $this->db->table('v_compte_client')
            ->where('telephone', $telephone)
            ->get()
            ->getRowArray();
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