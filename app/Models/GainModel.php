<?php

namespace App\Models;

use CodeIgniter\Database\BaseConnection;

class GainModel
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function parOperation()
    {
        return $this->db->table('v_gains_par_operation')->get()->getResultArray();
    }

    public function statistiquesGlobales()
    {
        return $this->db->table('v_statistiques_globales')->get()->getRowArray();
    }

    public function mensuels()
    {
        return $this->db->table('v_gains_mensuels')->get()->getResultArray();
    }

    public function repartition()
    {
        return $this->db->table('v_repartition_gains')->get()->getResultArray();
    }

    public function performanceOperateur()
    {
        return $this->db->table('v_performance_operateur')->get()->getResultArray();
    }
}