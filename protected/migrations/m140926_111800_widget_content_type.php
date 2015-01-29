<?php

class m140926_111800_widget_content_type extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{widget}}', 'content_type', 'TINYINT(1) DEFAULT 1');
	}

	public function down()
	{
		echo "m140926_111800_widget_content_type does not support migration down.\n";
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