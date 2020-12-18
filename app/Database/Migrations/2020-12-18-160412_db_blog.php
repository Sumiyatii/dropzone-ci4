<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DbBlog extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'		=> ['type' => 'int', 'constraint' => 11,'unsigned' => true, 'auto_increment' => true],
			'nama_foto'	=> ['type' => 'varchar', 'constraint' => 255],
			'token'		=> ['type' => 'varchar', 'constraint' => 255]
		]);

		$this->forge->addKey('id', true);
		$this->forge->createTable('foto');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('foto');
	}
}
