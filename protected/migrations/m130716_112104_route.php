<?php

class m130716_112104_route extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{route}}', array(
            'id' => 'pk',
            'name' => 'varchar(255) NOT NULL',
            'current_name' => 'varchar(255) NOT NULL',
            'title' => 'varchar(255) NOT NULL',
            'module' => 'varchar(255) NULL',
            'model' => 'varchar(255) NULL',
            'item_id' => 'INT NULL',
            'url' => 'varchar(255) NOT NULL',
            'full_url' => 'varchar(255) NOT NULL',
            'lang_id' => 'INT NOT NULL',
            'admin_menu'=>'TEXT NULL',
            'status' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'root' => 'int(11) unsigned DEFAULT NULL',
            'lft' => 'int(11) unsigned NOT NULL',
            'rgt' => 'int(11) unsigned NOT NULL',
            'level' => 'smallint(5) unsigned NOT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{route}}_{{language}}', '{{route}}', 'lang_id', '{{language}}', 'id');
	}

	public function down()
	{
        $this->dropForeignKey('fk_{{route}}_{{language}}', '{{route}}');
        $this->dropTable('{{route}}');
	}

}