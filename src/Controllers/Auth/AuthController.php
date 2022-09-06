<?php

namespace App\Controllers\Auth;

use Raydragneel\Authentication\Models\AccountModel;
use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        if($this->request->getMethod() === 'post'){
            $configAuth = config('Auth');
            $rules = [
                $configAuth->login_using => [
                    'label'  => lang("auth.{$configAuth->login_using}"),
                    'rules'  => 'required',
                    'errors' => []
                ],
                'password' => [
                    'label'  => lang("auth.password"),
                    'rules'  => 'required',
                    'errors' => []
                ],
            ];
            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $data = $this->getDataRequest();
            $auth = service('auth');
            $data_res = [
                "status" => false,
                "data" => []
            ];
            if ($auth->attempt($data)) {
                $message = $auth->message;
                $ses['username'] = $auth->user->username;
                $ses['name'] = $auth->user->name;
                $this->session->set($ses);
                $data_res['status'] = true;
                $data_res['message'] = $message;
                $data_res['data']['redir'] = base_url(config('Auth')->redirect_login);
                $this->response->setJSON($data_res)->setStatusCode(200)->send();
                die();
            } else {
                $message = $auth->message;
                $data_res['message'] = $message;
                $this->response->setJSON($data_res)->setStatusCode(400)->send();
                die();
            }
        }else{
            $data = [
                'page_title' => lang("global.login"),
                'list_users' => model(AccountModel::class)->findAll(),
            ];
            return renderView('auth/login', $data);
        }
    }
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url("login"));
    }
}
