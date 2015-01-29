<?php

class m130415_090835_pub_date_field extends CDbMigration
{
	public function up()
	{
        $this->alterColumn('{{post}}', 'pub_date', 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	}

	public function down()
	{
        $this->alterColumn('{{post}}', 'pub_date', 'varchar(100)');
	}
}