<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\PermissionModel;

class PermissionEntity extends BaseEntity
{
    protected $modelName = PermissionModel::class;
    protected $datamap = [
        'id' => 'permission_id'
    ];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    public function __construct(array $data = null)
	{
		parent::__construct($data);
	}

}
