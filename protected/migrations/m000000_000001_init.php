<?php

class m000000_000001_init extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('{{module}}', array(
            'id' => 'pk',
            'name' => 'VARCHAR(125) NOT NULL',
            'title' => 'VARCHAR(125) NOT NULL',
            'description' => 'string NOT NULL',
            'options' => 'text',
            'admin_controller' => 'string DEFAULT NULL',
            'version' => 'VARCHAR(45) NOT NULL',
            'author_name' => 'VARCHAR(125) NOT NULL',
            'author_url' => 'VARCHAR(125) NOT NULL',
            'author_email' => 'VARCHAR(125) NOT NULL',
            'status' => 'TINYINT(1) DEFAULT 1',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->createTable('{{widget}}', array(
            'id' => 'pk',
            'name' => 'VARCHAR(125) NOT NULL',
            'title' => 'VARCHAR(125) NOT NULL',
            'description' => 'string NOT NULL',
            'content' => 'TEXT NULL',
            'version' => 'VARCHAR(45) NOT NULL',
            'author_name' => 'VARCHAR(125) NOT NULL',
            'author_url' => 'VARCHAR(125) NOT NULL',
            'author_email' => 'VARCHAR(125) NOT NULL',
            'options' => 'TEXT',
            'status' => 'TINYINT(1) DEFAULT 1',
            'parent_id' => 'INT NULL',
            'type' => 'TINYINT(1) DEFAULT 1',
            'is_cached' => 'TINYINT(1) DEFAULT 1',
            'cache_time' => 'INT NULL',
            'order' => 'INT NULL',
            'position' => 'VARCHAR(125) NULL',
            'layout' => 'VARCHAR(125) NULL',
            'module' => 'VARCHAR(125) NULL',
            'controller' => 'VARCHAR(125) NULL',
            'action' => 'VARCHAR(125) NULL',
            'item_id' => 'INT NULL',

        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');


        $this->createTable('{{language}}', array(
            'id' => 'pk',
            'name' => 'VARCHAR(2) NOT NULL',
            'title' => 'VARCHAR(125) NOT NULL',
            'status' => 'TINYINT(1) DEFAULT 1',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->createTable('{{user}}', array(
            'id' => 'pk',
            'username' => 'string NOT NULL',
            'email' => 'string NULL',
            'password' => 'varchar(40) NOT NULL',
            'salt' => 'varchar(32) NOT NULL',
            'name' => 'string NOT NULL',
            'reg_date' => 'TIMESTAMP NOT NULL',
            'activation_code' => 'varchar(32) NULL',
            'role' => "varchar(128) NOT NULL DEFAULT 'user'",
            'status' => 'TINYINT(1) NOT NULL DEFAULT 0',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->createTable('{{auth_item}}', array(
            'name' => 'varchar(64) not null',
            'type' => 'integer not null',
            'description' => 'text',
            'bizrule' => 'text',
            'data' => 'text',
            'primary key (`name`)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->createTable('{{auth_item_child}}', array(
            'parent' => 'varchar(64) not null',
            'child' => 'varchar(64) not null',
            'primary key (`parent`,`child`)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{auth_item_child}}_{{auth_item}}0', '{{auth_item_child}}', 'parent', '{{auth_item}}', 'name', 'cascade', 'cascade');
        $this->addForeignKey('fk_{{auth_item_child}}_{{auth_item}}1', '{{auth_item_child}}', 'child', '{{auth_item}}', 'name', 'cascade', 'cascade');

        $this->createTable('{{auth_assignment}}', array(
            'itemname' => 'varchar(64) not null',
            'userid' => 'varchar(64) not null',
            'bizrule' => 'text',
            'data' => 'text',
            'primary key (`itemname`,`userid`)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{auth_assignment}}_{{auth_item}}', '{{auth_assignment}}', 'itemname', '{{auth_item}}', 'name', 'cascade', 'cascade');

        $this->insert('{{auth_item}}',
            array(
                'name'=>'admin',
                'type'=>2,
                'description'=>'Super admin',
                'data'=>serialize(array('is_admin'=>1))
            )
        );

        $this->insert('{{auth_item}}',
            array(
                'name'=>'user',
                'type'=>2,
                'description'=>'User role',
                'data'=>serialize(array('is_admin'=>0))
            )
        );

        $this->insert('{{auth_assignment}}',
            array(
                'itemname'=>'admin',
                'userid'=>1,
            )
        );


	}

	public function safeDown()
	{
        $this->dropForeignKey('fk_{{auth_assignment}}_{{auth_item}}', '{{auth_assignment}}');
        $this->dropTable('{{auth_assignment}}');

        $this->dropForeignKey('fk_{{auth_item_child}}_{{auth_item}}1', '{{auth_item_child}}');
        $this->dropForeignKey('fk_{{auth_item_child}}_{{auth_item}}0', '{{auth_item_child}}');
        $this->dropTable('{{auth_item_child}}');

        $this->dropTable('{{auth_item}}');

        $this->dropTable('{{user}}');
        $this->dropTable('{{language}}');
        $this->dropTable('{{widget}}');
        $this->dropTable('{{module}}');
	}
}