<?php

namespace App\Controllers\Main\Master;

use Raydragneel\Authentication\Entities\AccountEntity;
use Raydragneel\Authentication\Libraries\Gate;
use Raydragneel\Authentication\Models\AccountModel;

class AdminController extends BaseMainMasterController
{
    protected $modelName = AccountModel::class;
    public function initialize()
    {
        $this->rootView .= 'admin/';
    }
    public function index()
    {
        abort_if(Gate::allow(['admin_access']), 401, '401 ' . lang('global.api.unauthorized'));
        $data = [
            'page_title' => lang("cruds.admin.title_singular"),
            'url_add' => main_url('master/admin/add'),
            'url_datatable' => main_url('master/admin/datatable'),
            'url_edit' => main_url('master/admin/{0}/edit'),
            'url_delete' => main_url('master/admin/{0}/delete'),
            'url_purge' => main_url('master/admin/{0}/delete?purge=1'),
            'url_restore' => main_url('master/admin/{0}/restore'),
            'url_import' => main_url('master/admin/import'),
            '_with_datatable' => true
        ];
        return renderView('index', $data);
    }
    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            abort_if(Gate::allow(['admin_create']), 401, '401 ' . lang('global.api.unauthorized'), $this->request->getMethod() === 'post' ? 'api' : 'web');

            $rules = [
                'username' => [
                    'label'  => lang('cruds.account.fields.username'),
                    'rules'  => 'required|is_unique[accounts.username,account_id]',
                    'errors' => []
                ],
                'name' => [
                    'label'  => lang('cruds.account.fields.name'),
                    'rules'  => 'required',
                    'errors' => []
                ],
                'email' => [
                    'label'  => lang('cruds.account.fields.email'),
                    'rules'  => 'required',
                    'errors' => []
                ],
                'password' => [
                    'label'  => lang('cruds.account.fields.password'),
                    'rules'  => 'required|min_length[6]',
                    'errors' => []
                ],
            ];
            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang('global.api.errorValidation'), "data" => $this->validator->getErrors()]);
            }
            $data = $this->getDataRequest();
            $entity = new AccountEntity($data);
            $id = $entity->save();
            if ($id) {
                $admin = $this->model->find($id);
                $admin->assignRoles('Admin');
                return $this->response->setStatusCode(200)->setJSON(["status" => true, "message" => lang("global.api.create.success", [lang('cruds.admin.title_singular')]), "data" => [
                    'redir' => main_url('master/admin')
                ]], 200);
            } else {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("global.api.create.error", [lang('cruds.admin.title_singular')]), "data" => []]);
            }
        }
        abort_if(Gate::allow(['admin_create']), 401, '401 ' . lang('global.api.unauthorized'), $this->request->getMethod() === 'post' ? 'api' : 'web');
        $data = [
            'page_title' => lang('global.create', [lang('cruds.admin.title_singular')]),
            'url_process' => main_url('master/admin/add'),
            'admin'  => new AccountEntity(),
        ];
        return renderView('form', $data);
    }
    public function edit($id_encode = null)
    {
        if ($this->request->getMethod() === 'post') {
            abort_if(Gate::allow(['admin_edit']), 401, '401 ' . lang('global.api.unauthorized'), 'api');
            $admin = $this->model->findEncode($id_encode);
            abort_if($admin, 404, '404 ' . lang('global.api.data_not_found', [lang('cruds.admin.title_singular')]), 'api');
            $rules = [
                'username' => [
                    'label'  => lang('cruds.account.fields.username'),
                    'rules'  => "required|is_unique[accounts.username,account_id,{$admin->id}]",
                    'errors' => []
                ],
                'name' => [
                    'label'  => lang('cruds.account.fields.name'),
                    'rules'  => 'required',
                    'errors' => []
                ],
                'email' => [
                    'label'  => lang('cruds.account.fields.email'),
                    'rules'  => 'required',
                    'errors' => []
                ],
                'password' => [
                    'label'  => lang('cruds.account.fields.password'),
                    'rules'  => 'permit_empty|min_length[6]',
                    'errors' => []
                ],
            ];
            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => "Validasi Gagal", "data" => $this->validator->getErrors()]);
            }
            $data = $this->getDataRequest();
            $admin = $admin->fill($data);
            try {
                if ($admin->update()) {
                    return $this->response->setStatusCode(200)->setJSON(["status" => true, "message" => lang("global.api.edit.success", [lang('cruds.admin.title_singular')]), "data" => [
                        'redir' => main_url('master/admin')
                    ]], 200);
                } else {
                    return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("global.api.edit.error", [lang('cruds.admin.title_singular')]), "data" => []]);
                }
            } catch (\Exception $th) {
                if ($th->getMessage() === 'Tidak ada data untuk update.') {
                    return $this->response->setStatusCode(200)->setJSON(["status" => true, "message" => lang("global.api.edit.success", [lang('cruds.admin.title_singular')]), "data" => []]);
                }
                return $this->response->setStatusCode(200)->setJSON(["status" => false, "message" => $th->getMessage(), "data" => []]);
            }
        }
        abort_if(Gate::allow(['admin_edit']), 401, '401 ' . lang('global.api.unauthorized'));
        $admin = $this->model->findEncode($id_encode);
        abort_if($admin, 404, '404 ' . lang('global.api.data_not_found', [lang('cruds.admin.title_singular')]));
        $data = [
            'page_title' => lang('global.edit', [lang('cruds.admin.title_singular')]),
            'url_process' => main_url("master/admin/{$admin->id_encode}/edit"),
            'admin' => $admin,
            'url_previous_page' => $this->getPreviousPage(2)
        ];
        return renderView('form', $data);
    }

    public function datatable()
    {
        abort_if(Gate::allow(['admin_access']), 401, '401 ' . lang('global.api.unathorized'), 'api');
        $data = $this->getDataRequest();
        $like = [];
        $where = [
            'account_id !=' => $this->_auth->user->id
        ];
        if (!empty($_POST['search']['value'])) {
            $like['username'] = htmlspecialchars($_POST['search']['value']);
        }
        $join = [
            // 'accounts' => [
            //     'on' => 'nidn',
            //     'link' => 'username'
            // ]
        ];
        $params = ['select' => "accounts.*", 'like' => $like, 'where' => $where, 'join' => $join];
        if (can('admin_access')) {
            $params['withDeleted'] = true;
        }
        return $this->response->setStatusCode(200)->setJSON($this->datatable_get($params));
    }

    public function delete($id_encode = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            abort_if(Gate::allow(['admin_purge']), 401, '401 ' . lang('global.api.unathorized'), 'api');
            $admin = $this->model->findEncode($id_encode, true);
        } else {
            abort_if(Gate::allow(['admin_delete']), 401, '401 ' . lang('global.api.unathorized'), 'api');
            $admin = $this->model->findEncode($id_encode);
        }
        if ($admin) {
            if (isset($data['purge'])) {
                $delete = $admin->delete(true);
            } else {
                $delete = $admin->delete();
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang('global.api.purge.success', [lang('cruds.admin.title_singular')]);
                } else {
                    $message = lang('global.api.delete.success', [lang('cruds.admin.title_singular')]);
                }
                return $this->response->setStatusCode(200)->setJSON(["status" => true, "message" => $message, "data" => []]);
            } else {
                if (isset($data['purge'])) {
                    $message = lang('global.api.purge.error', [lang('cruds.admin.title_singular')]);
                } else {
                    $message = lang('global.api.purge.error', [lang('cruds.admin.title_singular')]);
                }
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => $message, "data" => []]);
            }
        }
        abort(404, '404 ' . lang('global.api.data_not_found', [lang('cruds.admin.title_singular')]), 'api');
    }
    public function restore($id_encode = null)
    {
        abort_if(Gate::allow(['admin_restore']), 401, '401 ' . lang('global.api.unathorized'), 'api');
        $admin = $this->model->findEncode($id_encode, true);
        if ($admin) {
            if ($admin->restore()) {
                return $this->response->setStatusCode(200)->setJSON(["status" => true, "message" => lang('global.api.restore.success', [lang('cruds.admin.title_singular')]), "data" => []]);
            } else {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang('global.api.restore.error', [lang('cruds.admin.title_singular')]), "data" => []]);
            }
        }
        abort(404, '404 ' . lang('global.api.data_not_found', [lang('cruds.admin.title_singular')]), 'api');
    }
}
