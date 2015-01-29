<?php

class m130419_072033_menu extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{menu}}', array(
            'id' => 'pk',
            'title' => 'varchar(128) NOT NULL',
            'image' => 'varchar(128) NULL',
            'route_id' => 'INT NULL',
            'root' => 'int(11) unsigned DEFAULT NULL',
            'lft' => 'int(11) unsigned NOT NULL',
            'rgt' => 'int(11) unsigned NOT NULL',
            'level' => 'smallint(5) unsigned NOT NULL',
            'status' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'parent_id' => 'INT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->createTable('{{menu_lang}}', array(
            'id'=>'pk',
            'menu_id'=>'INT NOT NULL',
            'lang_id'=>'VARCHAR(6) NOT NULL',
            'l_title'=>'VARCHAR(255) NOT NULL'
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{menu_lang}}_{{menu}}', '{{menu_lang}}', 'menu_id', '{{menu}}', 'id', 'cascade', 'cascade');
	}

	public function down()
	{
        $this->dropTable('{{menu}}');
	}
}