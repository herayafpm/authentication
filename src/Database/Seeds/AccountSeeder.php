<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\Authentication\Entities\AccountEntity;
use Raydragneel\Authentication\Models\AccountModel;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $password = '123456';
        $email = "mail.com";
        $accounts = [
            [
                'username' => 'superadmin',
                'name' => 'Superadmin',
                'email' => 'superadmin@' . $email,
                'email_verified_at' => null,
                'password' => $password,
                'roles' => ['Superadmin']
            ],
        ];
        foreach ($accounts as $r) {
            $account = new AccountEntity($r);
            $account_id = $account->save();
            if ($account_id) {
                $account = model(AccountModel::class)->find($account_id);
                $account->assignRoles($r['roles']);
            }
        }
    }
}
