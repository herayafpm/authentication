<?php

namespace Raydragneel\Authentication\Models;

use Raydragneel\Authentication\Entities\ModelAccountEntity;

class ModelAccountModel extends BaseModel
{
    protected $table            = 'model_account';
    protected $primaryKey       = 'model_ac_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = ModelAccountEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'model_link',
        'model_name',
        'model_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
