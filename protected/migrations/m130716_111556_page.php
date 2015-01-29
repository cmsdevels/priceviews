<?php

class m130716_111556_page extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{page}}', array(
            'id' => 'pk',
            'title' => 'varchar(255) NOT NULL',
            'content' => 'TEXT NULL',
            'pub_date' => 'TIMESTAMP NOT NULL',
            'author_id' => 'INT NOT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');
        $this->addForeignKey('fk_{{page}}_{{user}}', '{{page}}', 'author_id', '{{user}}', 'id');

        $this->createTable('{{page_lang}}', array(
            'id'=>'pk',
            'page_id'=>'INT NOT NULL',
            'lang_id'=>'VARCHAR(6) NOT NULL',
            'l_title'=>'VARCHAR(255) NOT NULL',
            'l_content'=>'TEXT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{page_lang}}_{{page}}', '{{page_lang}}', 'page_id', '{{page}}', 'id', 'cascade', 'cascade');
	}

	public function down()
	{
        $this->dropForeignKey('fk_{{page}}_{{user}}', '{{page}}');
        $this->dropTable('{{page}}');
	}
}