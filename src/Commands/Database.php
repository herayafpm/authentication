<?php

namespace Raydragneel\Authentication\Commands;

use CodeIgniter\CLI\BaseCommand;

class Database extends BaseCommand
{
    protected $group       = 'raydragneelauth';
    protected $name        = 'raydragneelauth:database';
    protected $description = 'Database Migrations & Seed';

    public function run(array $params)
    {
        echo command('migrate:refresh');
        echo command('db:seed InitSeeder');
    }
}