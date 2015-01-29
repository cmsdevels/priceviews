<?php

class m130911_075154_menu_fields_add extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{menu}}', 'type', 'TINYINT(1) NOT NULL DEFAULT 0');
        $this->addColumn('{{menu}}', 'url', 'VARCHAR(255) NULL');


	}

	public function down()
	{
	}
}