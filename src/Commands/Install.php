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
        $main_namespace = ucwords(config('Auth')->redirect_login);

        // Migrations Copy
        $path = __DIR__."/../Database/Migrations";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            if(!file_exists(APPPATH."/Database/Migrations/".$file)){
                copy($path."/".$file,APPPATH."/Database/Migrations/".$file.".php");
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
        if(!file_exists(APPPATH."/Controllers/BaseController.php")){
            copy(__DIR__."/../Controllers/BaseController.php",APPPATH."/Controllers/BaseController.php");
        }

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

        if(!file_exists(APPPATH."/Views/templates/".strtolower($main_namespace).".php")){
            copy(__DIR__."/../Views/templates/main.php",APPPATH."/Views/templates/".strtolower($main_namespace).".php");
        }
        if(!file_exists(APPPATH."/Config/Auth.php")){
            copy(__DIR__."/../Config/Auth.php",APPPATH."/Config/Auth.php");
        }

        if (!file_exists(APPPATH."/Views/".strtolower($main_namespace))) {
            mkdir(APPPATH."/Views/".strtolower($main_namespace), 0777, true);
        }
        $path = __DIR__."/../Views/main";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            if(is_dir($path."/".$file)){
                if(!file_exists(APPPATH."/Views/".strtolower($main_namespace)."/".$file)){
                    mkdir(APPPATH."/Views/".strtolower($main_namespace)."/".$file, 0777, true);
                }
                $path2 = $path."/".$file;
                $files2 = scandir($path2);
                $files2 = array_diff(scandir($path2), array('.', '..'));
                foreach ($files2 as $file2) {
                    if(is_dir($path2."/".$file2)){
                        if(!file_exists(APPPATH."/Views/".strtolower($main_namespace)."/".$file."/".$file2)){
                            mkdir(APPPATH."/Views/".strtolower($main_namespace)."/".$file."/".$file2, 0777, true);
                        }
                        $path3 = $path2."/".$file2;
                        $files3 = scandir($path3);
                        $files3 = array_diff(scandir($path3), array('.', '..'));
                        foreach ($files3 as $file3) {
                            if(!file_exists(APPPATH."/Views/".strtolower($main_namespace)."/".$file."/".$file2."/".$file3)){
                                copy($path3."/".$file3,APPPATH."/Views/".strtolower($main_namespace)."/".$file."/".$file2."/".$file3);
                            }
                        }
                    }else{
                        if(!file_exists(APPPATH."/Views/".strtolower($main_namespace)."/".$file."/".$file2)){
                            copy($path2."/".$file2,APPPATH."/Views/".strtolower($main_namespace)."/".$file."/".$file2);
                        }
                    }
                }
            }else{
                if(!file_exists(APPPATH."/Views/".strtolower($main_namespace)."/".$file)){
                    copy($path."/".$file,APPPATH."/Views/".strtolower($main_namespace)."/".$file);
                }
            }
        }


        $path = __DIR__."\..\..\auth_assets";
        $target = APPPATH."\..\public\auth_assets";
        if(!file_exists($target)){
            try {
                if(PHP_OS === 'WINNT'){
                    exec("mklink /J $target $path");
                    die();
                }
                symlink($path,$target);
            } catch (\Exception $th) {
                exec('ln -s '.$path." ".$target);
            }
        }

        // Main
        $path = __DIR__."/../Controllers/Main";
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        if (!file_exists(APPPATH."/Controllers/{$main_namespace}")) {
            mkdir(APPPATH."/Controllers/{$main_namespace}", 0777, true);
        }
        foreach ($files as $file) {
            $baseController = "Base{$main_namespace}Controller";
            if(is_dir($path."/".$file)){
                if (!file_exists(APPPATH."/Controllers/{$main_namespace}/".$file)) {
                    mkdir(APPPATH."/Controllers/{$main_namespace}/".$file, 0777, true);
                }
                $path2 = $path."/".$file;
                $files2 = scandir($path2);
                $files2 = array_diff(scandir($path2), array('.', '..'));
                foreach ($files2 as $file2) {
                    if(!file_exists(APPPATH."/Controllers/{$main_namespace}/".$file."/".$file2)){
                        $str_file=file_get_contents($path2."/".$file2);
                        $str_file=str_replace("App\Controllers\Main\\".$file, "App\Controllers\\".$main_namespace."\\".$file,$str_file);
                        $str_file=str_replace("BaseMainMasterController", "Base{$main_namespace}{$file}Controller",$str_file);
                        if($file2 == 'BaseMainMasterController.php'){
                            $str_file=str_replace("App\Controllers\Main\BaseMainController", "App\Controllers\\".$main_namespace."\Base{$main_namespace}Controller",$str_file);
                            $str_file=str_replace("BaseMainController", "Base{$main_namespace}Controller",$str_file);
                            $str_file=str_replace("main/master/", strtolower($main_namespace)."/".strtolower($file)."/",$str_file);
                            file_put_contents(APPPATH."/Controllers/{$main_namespace}/".$file."/"."Base{$main_namespace}{$file}Controller.php",$str_file);
                        }else{
                            file_put_contents(APPPATH."/Controllers/{$main_namespace}/".$file."/".$file2,$str_file);
                        }
                    }
                }
            }else{
                if($file == 'BaseMainController.php'){
                    $file_rep = "Base{$main_namespace}Controller";
                }else{
                    $file_rep = str_replace(".php","",$file);
                }
                if(!file_exists(APPPATH."/Controllers/{$main_namespace}/".$file_rep.".php")){
                    $str_file=file_get_contents($path."/".$file);
                    $str_file=str_replace("App\Controllers\Main", "App\Controllers\\".$main_namespace,$str_file);
                    $str_file=str_replace("BaseMainController", $baseController,$str_file);
                    if($file == 'BaseMainController.php'){
                        $str_file=str_replace("main/", strtolower($main_namespace)."/",$str_file);
                    }
                    file_put_contents(APPPATH."/Controllers/{$main_namespace}/".$file_rep.".php",$str_file);
                    // copy($path."/".$file,APPPATH."/Controllers/{$main_namespace}/".$file_rep);
                }
            }
        }
        die();
    }
}