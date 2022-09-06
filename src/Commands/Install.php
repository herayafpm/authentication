<?php

namespace Raydragneel\Authentication\Commands;

use CodeIgniter\CLI\BaseCommand;

class Install extends BaseCommand
{
    protected $group       = 'raydragneelauth';
    protected $name        = 'raydragneelauth:install';
    protected $description = 'Authentication Install';

    public function run(array $params)
    {
        // Migrations Copy
        $path = __DIR__."/../Database/Migrations";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            if(!file_exists(APPPATH."/Database/Migrations/".$file)){
                copy($path."/".$file,APPPATH."/Database/Migrations/".$file);
            }
        }
        // Seeder Copy
        $path = __DIR__."/../Database/Seeds";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            if(!file_exists(APPPATH."/Database/Seeds/".$file)){
                copy($path."/".$file,APPPATH."/Database/Seeds/".$file);
            }
        }

        // Basecontroller copy
        copy(__DIR__."/../Controllers/BaseController.php",APPPATH."/Controllers/BaseController.php");

        // Controller Auth Copy
        $path = __DIR__."/../Controllers/Auth";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        if (!file_exists(APPPATH."/Controllers/Auth")) {
            mkdir(APPPATH."/Controllers/Auth", 0777, true);
        }
        foreach ($files as $file) {
            if(!file_exists(APPPATH."/Controllers/Auth/".$file)){
                copy($path."/".$file,APPPATH."/Controllers/Auth/".$file);
            }
        }
        if (!file_exists(APPPATH."/Views/auth")) {
            mkdir(APPPATH."/Views/auth", 0777, true);
        }
        if(!file_exists(APPPATH."/Views/auth/login.php")){
            copy(__DIR__."/../Views/auth/login.php",APPPATH."/Views/auth/login.php");
        }
        if (!file_exists(APPPATH."/Views/templates")) {
            mkdir(APPPATH."/Views/templates", 0777, true);
        }
        if(!file_exists(APPPATH."/Views/templates/auth.php")){
            copy(__DIR__."/../Views/templates/auth.php",APPPATH."/Views/templates/auth.php");
        }
        if(!file_exists(APPPATH."/Config/Auth.php")){
            copy(__DIR__."/../Config/Auth.php",APPPATH."/Config/Auth.php");
        }


        $path = __DIR__."\..\..\auth_assets";
        $target = APPPATH."\..\public\auth_assets";
        try {
            if(PHP_OS === 'WINNT'){
                exec("mklink /J $target $path");
                die();
            }
            symlink($path,$target);
        } catch (\Exception $th) {
            exec('ln -s '.$path." ".$target);
        }

        die();
    }
}