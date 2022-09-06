<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthSeeder extends Seeder
{
    public function run()
    {
        $this->call(ModelAccountSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(RoleHasPermissionsSeeder::class);
    }
}
