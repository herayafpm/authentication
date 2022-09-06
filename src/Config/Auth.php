<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    public $appName = 'Hera Topup';
    public $login_using = 'username';
    public $redirect_login = 'admin';
}