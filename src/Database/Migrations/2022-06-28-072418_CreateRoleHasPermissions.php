<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoleHasPermissions extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'rolep_id'          => [
				'type'           => 'BIGINT',
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'role_id'       => [
				'type'           => 'INT',
                'constraint'     => 11,
				'unsigned'     => true
			],
			'permission_id'       => [
				'type'           => 'INT',
                'constraint'     => 11,
				'unsigned'     => true
			],
			'created_at'       => ['type' => 'DATETIME', 'null' => true]
		]);
		$this->forge->addKey('rolep_id', true);
        $this->forge->addForeignKey('permission_id','permissions','permission_id','CASCADE','CASCADE');
        $this->forge->addForeignKey('role_id','roles','role_id','CASCADE','CASCADE');
		$this->forge->createTable('role_has_permissions');
	}

	public function down()
	{
		$this->forge->dropTable('role_has_permissions');
	}
}
