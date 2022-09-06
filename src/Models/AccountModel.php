<?php

namespace Raydragneel\Authentication\Models;

use Raydragneel\Authentication\Entities\AccountEntity;

class AccountModel extends BaseModel
{
    protected $table            = 'accounts';
    protected $primaryKey       = 'account_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = AccountEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'name',
        'email',
        'email_verified_at',
        'password',
        'model_ac_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $columns    = [
        'account_id',
        'username',
        'name',
        'email',
        'email_verified_at',
        'password',
        'model_ac_id',
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
