<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\RoleHasPermissionsModel;

class RoleHasPermissionsEntity extends BaseEntity
{
    protected $modelName = RoleHasPermissionsModel::class;
    protected $datamap = [
        'id' => 'rolep_id'
    ];
    protected $dates   = ['created_at'];
    protected $casts   = [];
    public function __construct(array $data = null)
	{
		parent::__construct($data);
	}

}
