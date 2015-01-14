<?php

class m140106_113728_users_alter_add_avatar extends CDbMigration
{
	public function up()
	{
		$this->addColumn('{{users}}', 'avatar', 'VARCHAR(256)');
	}

	public function down()
	{
		echo "m140206_191918_users_alter_add_avatar does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}