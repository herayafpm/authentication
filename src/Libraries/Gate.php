<?php 
namespace Raydragneel\Authentication\Libraries;
require __DIR__."/../Helpers/main_helper.php";
class Gate{
    public static function allow($permission,$type = 'web')
    {
        if(is_string($permission)){
            $permission = [$permission];
        }
        $auth = service('auth',$type);
        $user = $auth->user ?? [];
        if($user){
            return $auth->user->can($permission);
        }
        return false;
    }
}
