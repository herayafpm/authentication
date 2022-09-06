<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\AccountHasPermissionsModel;

class AccountHasPermissionsEntity extends BaseEntity
{
    protected $modelName = AccountHasPermissionsModel::class;
    protected $datamap = [
        'id' => 'accountp_id'
    ];
    protected $dates   = ['created_at'];
    protected $casts   = [];
    public function __construct(array $data = null)
	{
		parent::__construct($data);
	}

}
