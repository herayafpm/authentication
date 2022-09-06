<?php

namespace Raydragneel\Authentication\Models;

use Raydragneel\Authentication\Entities\AccountHasRolesEntity;

class AccountHasRolesModel extends BaseModel
{
    protected $table            = 'account_has_roles';
    protected $primaryKey       = 'accountr_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = AccountHasRolesEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'account_id',
        'role_id',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

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
