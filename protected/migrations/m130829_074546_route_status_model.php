<?php

class m130829_074546_route_status_model extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{route}}', 'status_model', 'TINYINT(1) NOT NULL DEFAULT 1');
	}

	public function down()
	{
	}

}