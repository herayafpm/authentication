<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountHasRoles extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'accountr_id'          => [
				'type'           => 'BIGINT',
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'account_id'       => [
				'type'           => 'BIGINT',
				'unsigned'     => true
			],
			'role_id'       => [
				'type'           => 'INT',
                'constraint'     => 11,
				'unsigned'     => true
			],
			'created_at'       => ['type' => 'DATETIME', 'null' => true]
		]);
		$this->forge->addKey('accountr_id', true);
		$this->forge->addForeignKey('account_id','accounts','account_id','CASCADE','CASCADE');
        $this->forge->addForeignKey('role_id','roles','role_id','CASCADE','CASCADE');
		$this->forge->createTable('account_has_roles');
	}

	public function down()
	{
		$this->forge->dropTable('account_has_roles');
	}
}
