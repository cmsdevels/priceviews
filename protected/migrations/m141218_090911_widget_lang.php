<?php

class m141218_090911_widget_lang extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('{{widget_lang}}', array(
            'id' => 'pk',
            'widget_id' => 'INT NOT NULL',
            'lang_id' => 'VARCHAR(6) NOT NULL',
            'l_title' => 'VARCHAR(255) NOT NULL',
            'l_description' => 'string NOT NULL',
            'l_content' => 'TEXT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET utf8');

        $this->addForeignKey('fk_{{widget_lang}}_{{widget}}', '{{widget_lang}}', 'widget_id', '{{widget}}', 'id', 'cascade', 'cascade');

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_{{widget_lang}}_{{widget}}', '{{widget_lang}}');
        $this->dropTable('{{widget_lang}}');
    }

}