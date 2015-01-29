<?php

class m140529_093139_blog_ml extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{post_cat_lang}}', array(
            'id'=>'pk',
            'post_cat_id'=>'INT NOT NULL',
            'lang_id'=>'VARCHAR(6) NOT NULL',
            'l_name' => 'VARCHAR(128) NOT NULL',
        ));

        $this->addForeignKey('fk_{{post_cat_lang}}_{{post_cat}}', '{{post_cat_lang}}', 'post_cat_id', '{{post_cat}}', 'id', 'cascade', 'cascade');

        $this->createTable('{{post_lang}}', array(
            'id'=>'pk',
            'post_id'=>'INT NOT NULL',
            'lang_id'=>'VARCHAR(6) NOT NULL',
            'l_title' => 'VARCHAR(128) NOT NULL',
            'l_content' => 'TEXT NOT NULL',
        ));

        $this->addForeignKey('fk_{{post_lang}}_{{post}}', '{{post_lang}}', 'post_id', '{{post}}', 'id', 'cascade', 'cascade');


	}

	public function down()
	{
	}
}