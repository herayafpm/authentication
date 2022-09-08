<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateModelAccount extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'model_ac_id'          => [
				'type'           => 'INT',
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'model_link'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255'
			],
			'model_name'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'unique'        => true
			],
			'model_type'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'unique'        => true
			],
			'created_at'       => ['type' => 'DATETIME', 'null' => true],
			'updated_at'       => ['type' => 'DATETIME', 'null' => true],
			'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
		]);
		$this->forge->addKey('model_ac_id', true);
		$this->forge->createTable('model_account');
	}

	public function down()
	{
		$this->forge->dropTable('model_account');
	}
}
