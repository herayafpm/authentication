<?php

namespace Raydragneel\Authentication\Commands;

use CodeIgniter\CLI\BaseCommand;

class Storage extends BaseCommand
{
    protected $group       = 'raydragneelauth';
    protected $name        = 'raydragneelauth:storage';
    protected $description = 'Storage Link';

    public function run(array $params)
    {
        $path = APPPATH."\..\storage";
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        $target = APPPATH."\..\public\storage";
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
}