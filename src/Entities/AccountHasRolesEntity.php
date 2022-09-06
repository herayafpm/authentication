<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\AccountHasRolesModel;

class AccountHasRolesEntity extends BaseEntity
{
    protected $modelName = AccountHasRolesModel::class;
    protected $datamap = [
        'id' => 'accountr_id'
    ];
    protected $dates   = ['created_at'];
    protected $casts   = [];
    public function __construct(array $data = null)
	{
		parent::__construct($data);
	}

}
