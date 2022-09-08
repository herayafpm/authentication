<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccounts extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'account_id'          => [
				'type'           => 'BIGINT',
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'username'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'unique' => true
			],
			'name'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'email'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'          => true
			],
			'email_verified_at'       => ['type' => 'DATETIME', 'null' => true],
			'password'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'model_ac_id'       => [
				'type'           => 'INT',
				'null'		=> true,
				'unsigned'     => true
			],
			'link_account_id'       => [
				'type'           => 'INT',
				'null'		=> true,
				'unsigned'     => true
			],
			'created_at'       => ['type' => 'DATETIME', 'null' => true],
			'updated_at'       => ['type' => 'DATETIME', 'null' => true],
			'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
		]);
		$this->forge->addKey('account_id', true);
		$this->forge->addForeignKey('model_ac_id','model_account','model_ac_id');
		$this->forge->createTable('accounts');
	}

	public function down()
	{
		$this->forge->dropTable('accounts');
	}
}
