<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\Authentication\Models\RoleModel;

class RoleHasPermissionsSeeder extends Seeder
{
    public function run()
    {
        $superadmin = model(RoleModel::class)->where(['name' => 'Superadmin'])->first();
        if($superadmin){
            $superadmin->assignPermissions('*');
        }
    }
}