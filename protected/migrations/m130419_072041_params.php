<?php

class m130419_072041_params extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{params}}', array(
            'id' => 'pk',
            'key' => 'varchar(128) NOT NULL',
            'desc' => 'varchar(128) NOT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->insert('{{params}}',
            array(
                'key'=>'adminEmail',
                'desc'=>'Administrator\'s email',
            )
        );
        $this->insert('{{params}}',
            array(
                'key'=>'forward',
                'desc'=>'Default action',
            )
        );

        $this->insert('{{params}}',
            array(
                'key'=>'updateServer',
                'desc'=>'Update server',
            )
        );
	}

	public function down()
	{
        $this->dropTable('{{params}}');
	}
}