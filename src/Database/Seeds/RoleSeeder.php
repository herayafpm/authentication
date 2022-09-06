<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\Authentication\Entities\RoleEntity;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Superadmin',
            ],
            [
                'name' => 'Admin'
            ],
        ];
        foreach ($roles as $r) {
            $role = new RoleEntity($r);
            $role->save();
        }
    }
}
