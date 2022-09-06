<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\ModelAccountModel;

class ModelAccountEntity extends BaseEntity
{
    protected $modelName = ModelAccountModel::class;
    protected $datamap = [
        'id' => 'model_ac_id'
    ];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    public function __construct(array $data = null)
	{
		parent::__construct($data);
	}

}
