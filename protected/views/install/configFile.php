<?php echo "<?php";
 echo "
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'".$model->name."',
    'charset'=>'utf-8',
    'sourceLanguage' => 'en',
    'language' => '".$model->language."',

    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.modules.*',
        'application.components.*',
        'application.components.cms.*',
        'application.helpers.*',
    ),

    'modules'=>require(dirname(__FILE__) . '/modules.php'),

    // application components
    'components'=>array(
        'widgetFactory' => array(
            'widgets' => array(
                'CJuiDatePicker' => array(
                    'themeUrl' => '/css/admin/jqueryui',
                    'theme' => 'admin',
                ),
                'EJuiDateTimePicker' => array(
                    'themeUrl' => '/css/admin/jqueryui',
                    'theme' => 'admin',
                ),
            ),
        ),
        'user'=>array(
            'class' => 'application.components.WebUser',
            'allowAutoLogin'=>true,
        ),
        'authManager' => array(
            'class' => 'application.components.cms.CmsAuthManager',
            'defaultRoles' => array('guest'),
            'itemTable' => '{{auth_item}}',
            'itemChildTable' => '{{auth_item_child}}',
            'assignmentTable' => '{{auth_assignment}}',
        ),
        'request'=>array(
            'class'=>'HttpRequest',
        ),
        'urlManager'=>array(
            'class'=>'DLanguageUrlManager',
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                // Site index
                '/'=>'site/index',
                'admin/<controller:\w+>/<action:\w+>'=>'admin/<controller>/<action>',
                'admin/<module>/<controller:\w+>/<action:\w+>'=>'<module>/admin/<controller>/<action>',
                'admin'=>'/admin/default/index'
            ),
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=".$model->db_host.";dbname=".$model->db_dbname."',
            'emulatePrepare' => true,
            'username' => '".$model->db_username."',
            'password' => '".$model->db_password."',
            'charset' => 'utf8',
            'tablePrefix'=>'".$model->db_tablePrefix."',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
        ),
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*array(
                    'class'=>'CWebLogRoute',
                ),*/
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>require(dirname(__FILE__) . '/params.php'),
    'theme'=>'default',
);" ?>