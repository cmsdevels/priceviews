<?php

class m000000_000001_init extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('{{post_cat}}', array(
            'id' => 'pk',
            'name' => 'VARCHAR(128) NOT NULL',
            'seo_link' => 'VARCHAR(128) NOT NULL',
            'pub_date' => 'TIMESTAMP NOT NULL',
            'status' => 'TINYINT(1) NOT NULL DEFAULT 1',
        ), 'ENGINE=InnoDB');

        $this->createTable('{{post}}', array(
            'id' => 'pk',
            'title' => 'VARCHAR(128) NOT NULL',
            'content' => 'TEXT NOT NULL',
            'tags' => 'TEXT NOT NULL',
            'seo_link' => 'VARCHAR(128) NOT NULL',
            'pub_date' => 'varchar(100) NOT NULL',
            'status' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'cat_id' => 'INT NULL',
            'user_id' => 'INT NOT NULL',
        ), 'ENGINE=InnoDB');

        $this->addForeignKey('fk_{{post}}_{{post_cat}}', '{{post}}', 'cat_id', '{{post_cat}}', 'id');
        $this->addForeignKey('fk_{{post}}_{{user}}', '{{post}}', 'user_id', '{{user}}', 'id');

        $this->createTable('{{post_tag}}', array(
            'name' => 'VARCHAR(128) NOT NULL',
            'frequency' => 'INT NOT NULL DEFAULT 1',
        ), 'ENGINE=InnoDB');
	}

	public function safeDown()
	{

	}
}