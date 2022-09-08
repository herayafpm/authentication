<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountHasPermissions extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'accountp_id'          => [
				'type'           => 'BIGINT',
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'account_id'       => [
				'type'           => 'BIGINT',
				'unsigned'     => true
			],
			'permission_id'       => [
				'type'           => 'INT',
                'constraint'     => 11,
				'unsigned'     => true
			],
			'created_at'       => ['type' => 'DATETIME', 'null' => true]
		]);
		$this->forge->addKey('accountp_id', true);
        $this->forge->addForeignKey('account_id','accounts','account_id','CASCADE','CASCADE');
        $this->forge->addForeignKey('permission_id','permissions','permission_id','CASCADE','CASCADE');
		$this->forge->createTable('account_has_permissions');
	}

	public function down()
	{
		$this->forge->dropTable('account_has_permissions');
	}
}
