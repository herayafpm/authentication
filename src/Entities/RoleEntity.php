<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\PermissionModel;
use Raydragneel\Authentication\Models\RoleHasPermissionsModel;
use Raydragneel\Authentication\Models\RoleModel;

class RoleEntity extends BaseEntity
{
    protected $modelName = RoleModel::class;
    protected $datamap = [
        'id' => 'role_id'
    ];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    protected $roleHasPermissionsModel = null;
    protected $modelPermission = null;
    public function __construct(array $data = null)
	{
		parent::__construct($data);
        $this->roleHasPermissionsModel = model(RoleHasPermissionsModel::class);
        $this->modelPermission = model(PermissionModel::class);
	}

    public function getPermissionNames()
    {
        return $this->roleHasPermissionsModel->select("permission.name")->with([['table' => 'permissions','table_as' => 'permission','on' => 'permission_id','link' => 'permission_id']])->where(['role_id' => $this->role_id])->findColumn('name') ?? [];
    }

    public function assignPermissions($permissions = '',$exceptPermissions = '')
    {
        if($permissions === '*'){
            $permissions = $this->modelPermission->findColumn('name');
        }
        if(is_string($permissions)){
            $permissions = [$permissions];
        }
        if(is_string($exceptPermissions)){
            $exceptPermissions = [$exceptPermissions];
        }
        $role_permissions = $this->permission_names;
        foreach ($permissions as $permission) {
            if(!in_array($permission,$role_permissions)){
                $r = $this->roleHasPermissionsModel->with([['table' => 'permissions','table_as' => 'permission','table_as' => 'permission','link' => 'permission_id','on' => 'permission_id']])->where(['role_id' => $this->role_id,'permission.name' => $permission])->first();
                if($r){
                    $r->delete(true);
                }
            }
        }
        $permissions_diff = array_diff($permissions,$role_permissions);
        foreach ($permissions_diff as $permission) {
            if(!in_array($permission,$exceptPermissions)){
                $permission_entity = $this->modelPermission->select('permission_id')->where(['name' => $permission])->first();
                if($permission_entity){
                    $roleHasPermissionsModel = new RoleHasPermissionsEntity(['role_id' => $this->role_id,'permission_id' => $permission_entity->permission_id]);
                    $roleHasPermissionsModel->save();
                }
            }
        }
        return true;
    }

}