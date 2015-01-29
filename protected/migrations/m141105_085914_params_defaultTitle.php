<?php

class m141105_085914_params_defaultTitle extends CDbMigration
{
	public function up()
	{
		$this->insert('{{params}}',array(
			'key' => 'defaultTitle',
			'desc' => 'Default title',
			'type' => Params::TYPE_PLAIN_TEXT
		));
	}

	public function down()
	{
		echo "m141105_085914_params_defaultTitle does not support migration down.\n";
		return false;
	}

}