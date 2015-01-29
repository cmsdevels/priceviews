<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Установка системы',
    'charset'=>'utf-8',
    'sourceLanguage' => 'ru',

    'defaultController' => 'install',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.components.cms.*',
        'application.components.*',
        'application.models.*',
    ),
    'components'=>array(
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
    )
);