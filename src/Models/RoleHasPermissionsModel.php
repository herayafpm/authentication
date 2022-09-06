<?php

namespace Raydragneel\Authentication\Models;

use Raydragneel\Authentication\Entities\RoleHasPermissionsEntity;

class RoleHasPermissionsModel extends BaseModel
{
    protected $table            = 'role_has_permissions';
    protected $primaryKey       = 'rolep_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = RoleHasPermissionsEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'role_id',
        'permission_id',
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
