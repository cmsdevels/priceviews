<?php

Yii::import('application.models.Params');

class m141002_100809_params_type extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{params}}', 'type', 'TINYINT(2) DEFAULT 0');

		$this->update('{{params}}', array('type'=>Params::TYPE_EMAIL), "`key`='adminEmail'");
		$this->update('{{params}}', array('type'=>Params::TYPE_PLAIN_TEXT), "`key`='forward'");
		$this->update('{{params}}', array('type'=>Params::TYPE_URL), "`key`='updateServer'");
	}

	public function down()
	{
		echo "m141002_100809_params_type does not support migration down.\n";
		return false;
	}
}