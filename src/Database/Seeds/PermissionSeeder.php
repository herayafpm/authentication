<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\Authentication\Entities\PermissionEntity;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'home_access'
            ],
            [
                'name' => 'manajemen_master'
            ],
            [
                'name' => 'admin_access'
            ],
            [
                'name' => 'admin_create'
            ],
            [
                'name' => 'admin_edit'
            ],
            [
                'name' => 'admin_delete'
            ],
            [
                'name' => 'admin_purge'
            ],
            [
                'name' => 'admin_restore'
            ],
        ];
        foreach ($permissions as $r) {
            $permission = new PermissionEntity($r);
            $permission->save();
        }
    }
}