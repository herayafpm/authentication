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
                'name' => 'account_access'
            ],
            [
                'name' => 'account_create'
            ],
            [
                'name' => 'account_edit'
            ],
            [
                'name' => 'account_delete'
            ],
            [
                'name' => 'account_purge'
            ],
            [
                'name' => 'account_restore'
            ],
        ];
        foreach ($permissions as $r) {
            $permission = new PermissionEntity($r);
            $permission->save();
        }
    }
}