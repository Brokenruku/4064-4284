<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    
    protected $table = 'promotion_meme_operateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'pourcentage'];
    protected $returnType = 'array';

    public function listePromotion()
    {
        return $this->db->table('v_promo')->get()->getResultArray();
    }
}