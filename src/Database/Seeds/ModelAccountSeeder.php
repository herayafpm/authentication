<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\Authentication\Entities\ModelAccountEntity;

class ModelAccountSeeder extends Seeder
{
    public function run()
    {
        $models = [
            // [
            //     'model_name' => 'Admin',
            //     'model_type' => 'App/Models/AdminModel',
            //     'model_link' => 'username'
            // ],
        ];
        foreach ($models as $r) {
            $model = new ModelAccountEntity($r);
            $model->save();
        }
    }
}
