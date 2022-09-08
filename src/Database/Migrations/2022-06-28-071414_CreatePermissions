<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissions extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'permission_id'          => [
				'type'           => 'INT',
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'name'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'unique'        => true
			],
			'created_at'       => ['type' => 'DATETIME', 'null' => true],
			'updated_at'       => ['type' => 'DATETIME', 'null' => true],
		]);
		$this->forge->addKey('permission_id', true);
		$this->forge->createTable('permissions');
	}

	public function down()
	{
		$this->forge->dropTable('permissions');
	}
}
