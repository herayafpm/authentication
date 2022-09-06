<?php

namespace Raydragneel\Authentication\Entities;

use Raydragneel\Authentication\Models\AccountHasPermissionsModel;
use Raydragneel\Authentication\Models\AccountHasRolesModel;
use Raydragneel\Authentication\Models\AccountModel;
use Raydragneel\Authentication\Models\ModelAccountModel;
use Raydragneel\Authentication\Models\RoleHasPermissionsModel;
use Raydragneel\Authentication\Models\RoleModel;

class AccountEntity extends BaseEntity
{
    protected $modelName = AccountModel::class;
    protected $datamap = [
        'id' => 'account_id',
        'jenis' => null
    ];
    protected $exceptColumnDatatable = [
		'account_id',
		'id',
        'password',
        'model_ac_id',
        'link_account_id'
	];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    protected $accountHasRolesModel = null;
    protected $accountHasPermissionsModel = null;
    protected $roleHasPermissionsModel = null;
    protected $modelRole = null;
    protected $modelAccountModel = null;
    public function __construct(array $data = null)
	{
        parent::__construct($data);
        $this->modelAccountModel = model(ModelAccountModel::class);
        $this->accountHasPermissionsModel = model(AccountHasPermissionsModel::class);
        $this->roleHasPermissionsModel = model(RoleHasPermissionsModel::class);
        $this->accountHasRolesModel = model(AccountHasRolesModel::class);
        $this->modelRole = model(RoleModel::class);
	}

    public function hashPassword()
    {
        $this->password = password_hash($this->password,PASSWORD_DEFAULT);
    }
    public function save()
	{
        $this->hashPassword();
        if(isset($this->model_name)){
            $modelAccount = $this->modelAccountModel->where(['model_name' => $this->model_name])->first();
            if($modelAccount){
                $this->model_ac_id = $modelAccount->model_ac_id;
            }
        }
        if($this->model->save($this)){
            $insertId = $this->model->getInsertID();
            if(isset($this->data_pengguna)){
                $account = $this->model->find($insertId);
                if($account){
                    $account->saveDataPengguna($this->data_pengguna);
                }
            }
            return $insertId;
        }else{
            return false;
        }
	}

    public function update()
	{
		try {
            $updated = $this->model->update($this->id,$this);
            if($updated){
                if($this->hasChanged('model_ac_id')){
                    $beforeModelAccount = $this->modelAccountModel->find($this->before_model_ac_id);
                    $beforeModelAccountModel = model(str_replace("/","\\",$beforeModelAccount->model_type));
                    $cek = $beforeModelAccountModel->where([$beforeModelAccount->model_link => $this->username])->first();
                    if($cek){
                        $cek->delete(true);
                    }
                    $modelAccount = $this->model_account;
                    $modelPengguna = $this->model_pengguna;
                    if(isset($this->model_pengguna)){
                        $data[$modelAccount->model_link] = $this->username;
                        if($modelPengguna->getReturnType() !== 'array'){
                            $entityStr = $modelPengguna->getReturnType();
                            $entity = new $entityStr($data);
                            $entity->save();
                        }else{
                            $modelPengguna->save($data);
                        }
                    }
                }
            }
			return $updated;
		} catch (\Exception $th) {
			//throw $th;
			return false;
		}
	}

    public function getJenis()
    {
        return $this->modelAccountModel->find($this->model_ac_id)->model_name ?? null;
    }
    public function getModelAccount()
    {
        return $this->modelAccountModel->find($this->model_ac_id) ?? null;
    }
    public function getModelPengguna()
    {
        return model(str_replace("/","\\",$this->model_account->model_type));
    }

    public function saveDataPengguna($data)
    {
        $modelAccount = $this->model_account;
        if($modelAccount){
            $modelPengguna = $this->model_pengguna;
            if(isset($this->model_pengguna)){
                $data[$modelAccount->model_link] = $this->username;
                if($modelPengguna->getReturnType() !== 'array'){
                    $entityStr = $modelPengguna->getReturnType();
                    $entity = new $entityStr($data);
                    return $entity->save();
                }
                return $modelPengguna->save($data);
            }
        }else{
            return false;
        }
    }


    public function getRoleNames()
    {
        return $this->accountHasRolesModel->select("role.name")->with([['table' => 'roles','table_as' => 'role','on' => 'role_id','link' => 'role_id']])->where(['account_id' => $this->account_id])->findColumn('name') ?? [];
    }


    public function assignRoles($roles = '')
    {
        if(is_string($roles)){
            $roles = [$roles];
        }
        $account_roles = $this->role_names;
        foreach($account_roles as $role){
            if(!in_array($role,$account_roles)){
                $r = $this->accountHasRolesModel->with([['table' => 'roles','table_as' => 'role','table_as' => 'role','link' => 'role_id','on' => 'role_id']])->where(['account_id' => $this->account_id,'role.name' => $role])->first();
                if($r){
                    $r->delete(true);
                }
            }
        }
        $roles_diff = array_diff($roles,$account_roles);
        foreach ($roles_diff as $role) {
            $role_entity = $this->modelRole->select('role_id')->where(['name' => $role])->first();
            if($role_entity){
                $accountHasRolesModel = new AccountHasRolesEntity(['account_id' => $this->account_id,'role_id' => $role_entity->role_id]);
                $accountHasRolesModel->save();
            }
        }
        return true;
    }

    public function getPermissions()
    {
        $permissions = $this->accountHasPermissionsModel->select('permission.name')->with([['table' => 'permissions','table_as' => 'permission','on' => 'permission_id','link' => 'permission_id']])->where('account_id',$this->account_id)->findColumn('name') ?? [];
        $roles = $this->role_names;
        foreach ($roles as $role) {
            $role_detail = $this->modelRole->where(['name' => $role])->first();
            if($role_detail){
                array_push($permissions,...$this->roleHasPermissionsModel->select('permission.name')->with([['table' => 'permissions','table_as' => 'permission','on' => 'permission_id','link' => 'permission_id']])->where(['role_id' => $role_detail->id])->findColumn('name') ?? []);
            }
        }
        return $permissions;
    }


    public function can($permissions)
    {
        if(is_string($permissions)){
            $permissions = [$permissions];
        }
        $account_perms = $this->permissions;
        foreach ($permissions as $perm) {
            if(in_array($perm,$account_perms)){
                return true;
            }
        }
    }
    
    public function getPengguna()
    {
        return $this->getModelPengguna()->where([$this->modelAccount->model_link => $this->username])->first();
    }
    

    public function getUserPhoto()
    {
        if(file_exists(APPPATH."../storage/user_photo/".$this->username.".png")){
            return storage("user_photo/".$this->username.".png");
        }
        return asset('img/user2-160x160.jpg');
    }

}