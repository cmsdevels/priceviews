<?php

class m140602_075317_metaDataBehavior extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{meta_data}}', array(
            'id' => 'pk',
            'model' => 'varchar(128) NOT NULL',
            'model_id' => 'INT NOT NULL',
            'meta_title' => 'varchar(255) NULL',
            'meta_keywords' => 'TEXT NULL',
            'meta_description' => 'TEXT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->createTable('{{meta_data_lang}}', array(
            'id'=>'pk',
            'lang_id'=>'VARCHAR(6) NOT NULL',
            'data_id'=>'INT NOT NULL',
            'l_meta_title' => 'varchar(255) NULL',
            'l_meta_keywords' => 'TEXT NULL',
            'l_meta_description' => 'TEXT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{meta_data_lang}}_{{meta_data}}', '{{meta_data_lang}}', 'data_id', '{{meta_data}}', 'id', 'cascade', 'cascade');
	}

	public function down()
	{
		echo "m140602_075317_metaDataBehavior does not support migration down.\n";
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