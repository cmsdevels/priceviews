<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'PriceViews Console Application',
	'sourceLanguage' => 'en',
    'language' => 'ru',
	'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=an',
            'emulatePrepare' => true,
            'username' => 'an',
            'password' => '123123',
            'charset' => 'utf8',
            'tablePrefix'=>'tbl_',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
        ),
	),
);