<?php

class m141125_084911_add_to_params_default_separator extends CDbMigration
{
	public function up()
	{
		$this->insert('{{params}}',array(
			'key' => 'defaultSeparator',
			'desc' => 'Default separator',
			'type' => Params::TYPE_PLAIN_TEXT
		));
	}

	public function down()
	{
		echo "m141125_084911_add_to_params_default_separator does not support migration down.\n";
		return false;
	}

}