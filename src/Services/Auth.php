<?php

namespace Raydragneel\Authentication\Services;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Raydragneel\Authentication\Models\AccountModel;

class Auth
{
    public $message = "";
    public $user = null;
    protected $modelName = AccountModel::class;
    protected $model = null;
    public function __construct(RequestInterface $request, ResponseInterface $response, $args = [])
    {
        $this->request = $request;
        $this->response = $response;
        $this->message = "";
        $this->model = model($this->modelName);
        $this->checkUser($args);
    }

    public function checkUser($args)
    {
        if(empty($args['type']) || !isset($args['type'])){
            $session = service('session');
            if($session->get(config('Auth')->login_using)){
                $this->fillUser($session->get(config('Auth')->login_using));
            }
        }else{
            if ($this->request->hasHeader('X-SISPP-key')) {
                $accountKey = $this->request->getHeader('X-SISPP-key')->getValue() ?? '';
                if (empty($accountKey)) {
                    abort(401,"401 Unauthorized",'api');
                }
                if (strpos($accountKey, 'Bearer ') === false) {
                    abort(401,"401 Unauthorized",'api');
                }
                $accountKey = explode(" ", $accountKey);
                if (sizeof($accountKey) < 2) {
                    abort(401,"401 Unauthorized",'api');
                }
                try {
                    $jwt = ClaJWT::decode($accountKey[1]);
                    $this->fillUser($jwt[config('Auth')->login_using]);
                } catch (\Exception $th) {
                    abort(401,"401 Unauthorized",'api');
                }
            }
        }
    }

    public function fillUser($user)
    {
        if(is_string($user)){
            $this->user = $this->model->where([config('Auth')->login_using => $user])->first();
        }else{
            $this->user = $user;
        }
    }

    public function attempt($data)
    {
        $account = $this->model->where([config('Auth')->login_using => $data[config('Auth')->login_using]])->first();
        if($account){
            if(password_verify($data['password'],$account->password)){
                $this->fillUser($account);
                $this->message = lang("auth.loginSuccess",['name' => $account->name]);
                return true;
            }
        }
        $this->message = lang("auth.loginFailed",[lang('auth.'.config('Auth')->login_using)]);
        return false;
    }

}
