<?php

class m150129_143446_init extends CDbMigration
{
	public function up()
	{

        $this->createTable('{{offer}}', array(
            'id'=>'pk',
            'create_date'=>'TIMESTAMP NOT NULL',
            'status'=>'INT(1) NOT NULL',
            'title'=>'VARCHAR(255) NOT NULL',
            'description'=>'TEXT NULL',
            'content'=>'TEXT NULL',
            'image'=>'VARCHAR(255) NOT NULL',
        ));

        $this->createTable('{{offer_items}}', array(
            'id'=>'pk',
            'offer_id'=>'INT(11) NOT NULL',
            'create_date'=>'TIMESTAMP NOT NULL',
            'status'=>'INT(1) NOT NULL',
            'title'=>'VARCHAR(255) NOT NULL',
            'description'=>'TEXT NULL',
            'price'=>'VARCHAR(255) NOT NULL',
            'link'=>'VARCHAR(255) NOT NULL',
            'image'=>'VARCHAR(255) NOT NULL',
            'offer_logo'=>'VARCHAR(255) NOT NULL',
        ));
        $this->addForeignKey('fk_{{offer}}_{{offer_items}}', '{{offer_items}}', 'offer_id', '{{offer}}', 'id');
    }

	public function down()
	{
		echo "m150129_143446_init does not support migration down.\n";
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