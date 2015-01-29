<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weblogic
 * Date: 6/8/12
 * Time: 12:16 PM
 * To change this template use File | Settings | File Templates.
 */
class Install extends CFormModel
{
    public $name;
    public $db_host = 'localhost';
    public $db_dbname;
    public $db_username;
    public $db_password;
    public $db_tablePrefix = 'tbl_';

    public $admin_email;
    public $admin_username;
    public $admin_pass;
    public $language;

    public static $languageCodes = array(
        "en" => "English",
        "ru" => "Russian",
        "uk" => "Ukrainian",
    );


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('name, db_host, db_dbname, db_username, db_password, db_tablePrefix, admin_email, admin_username, admin_pass, language', 'required'),
            // Check connection to DB
            array('db_host', 'checkConnect'),
            array('name', 'checkDirectories'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'name'=> Yii::t('core/admin', 'Site title'),
            'language'=>Yii::t('core/admin', 'Site language'),
            'db_host'=> Yii::t('core/admin', 'Database host'),
            'db_dbname'=> Yii::t('core/admin', 'Database name'),
            'db_username'=>Yii::t('core/admin', 'Database user'),
            'db_password'=>Yii::t('core/admin', 'Database password'),
            'db_tablePrefix'=>Yii::t('core/admin','The default prefix for table names'),
            'admin_email'=>Yii::t('core/admin','Admin email'),
            'admin_username'=>Yii::t('core/admin','Admin username'),
            'admin_pass'=>Yii::t('core/admin','Admin password'),
        );
    }

    /**
     * Check connection to DB
     */
    public function checkConnect($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $connection = @mysql_connect($this->db_host, $this->db_username, $this->db_password);
            if ($connection)
                $connection = @mysql_select_db($this->db_dbname, $connection);

            if(!$connection)
                $this->addError('db_host',Yii::t('core/admin', 'Cannot connect to the database.'));
        }
    }


    /**
     * Checking create directories for temporary files FuriaCMS
     */
    public function checkDirectories($attribute,$params)
    {
        $baseDir = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

        $updatesDir = $baseDir.'updates'.DIRECTORY_SEPARATOR;
        if (!is_dir($updatesDir.'modules'))
            $this->addError('name', Yii::t('core/admin', 'Please create a folder ').'/updates/modules.');
        elseif (!is_writable($updatesDir.'modules'))
            $this->addError('name', Yii::t('core/admin', 'Set write permissions to the folder ').'/updates/modules.');

        if (!is_dir($updatesDir.'archives'))
            $this->addError('name', Yii::t('core/admin', 'Please create a folder ').'/updates/archives.');
        elseif (!is_writable($updatesDir.'archives'))
            $this->addError('name', Yii::t('core/admin', 'Set write permissions to the folder ').'/updates/archives.');

        $configDir = Yii::app()->basePath.DIRECTORY_SEPARATOR.'config';
        if (!is_writable($configDir))
            $this->addError('name', Yii::t('core/admin', 'Set write permissions to the folder ').'/protected/config.');

        $modulesDir = Yii::app()->basePath.DIRECTORY_SEPARATOR.'modules';
        if (!is_writable($modulesDir))
            $this->addError('name', Yii::t('core/admin', 'Set write permissions to the folder ').'/protected/modules.');

        $settingsUploadDir = $baseDir.'upload'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR;
        if (!is_dir($settingsUploadDir))
            $this->addError('name', Yii::t('core/admin', 'Please create a folder ').'/upload/settings.');
        elseif (!is_writable($settingsUploadDir))
            $this->addError('name', Yii::t('core/admin', 'Set write permissions to the folder ').'/upload/settings.');
    }


    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function setup()
    {
        if ($this->validate())
        {
            $configFile = Yii::app()->basePath.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."main.php";
            $configString = Yii::app()->controller->renderPartial('configFile',array('model'=>$this), true);

            $config = fopen($configFile, 'w+');
            if ($config)
            {
                fwrite($config, $configString);

                fclose($config);

                @chmod($configFile, 0666);
            }

            $consoleConfigFile = Yii::app()->basePath.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."console.php";
            $consoleConfigString = Yii::app()->controller->renderPartial('consoleFile',array('model'=>$this), true);

            $consoleConfig = fopen($consoleConfigFile, 'w+');
            if ($consoleConfig)
            {
                fwrite($consoleConfig, $consoleConfigString);

                fclose($consoleConfig);

                @chmod($consoleConfigFile, 0666);
            }

            $paramsFile = Yii::app()->basePath.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."params.php";
            $paramsString = Yii::app()->controller->renderPartial('paramsFile',array('model'=>$this), true);
            $params = fopen($paramsFile, 'w+');
            if ($params)
            {
                fwrite($params, $paramsString);

                fclose($params);

                @chmod($paramsFile, 0666);
            }

            // Create empty modules file
            $modulesFile = Yii::app()->basePath.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."modules.php";
            $modulesString = "<?php return array(); ?>";
            $modules = fopen($modulesFile, 'w+');
            if ($modules)
            {
                fwrite($modules, $modulesString);

                fclose($modules);

                @chmod($modulesFile, 0666);
            }


            $connectionString = "mysql:host=".$this->db_host.";dbname=".$this->db_dbname;
            $connection=new CDbConnection($connectionString,$this->db_username,$this->db_password);
            $connection->tablePrefix = $this->db_tablePrefix;
            $connection->active=true;
            $connection->createCommand('ALTER DATABASE '.$this->db_dbname.' CHARACTER SET utf8 COLLATE utf8_general_ci;')->execute();
            Yii::app()->setComponent('db', $connection);


            // Install core admin
            $runner=new CConsoleCommandRunner();
            $runner->commands=array(
                'migrate' => array(
                    'class' => 'system.cli.commands.MigrateCommand',
                    'migrationTable' => '{{migration_core}}',
                    'interactive' => false,
                ),
            );

            ob_start();
            $runner->run(array(
                'yiic',
                'migrate',
            ));
            ob_get_clean();

            // Create admin user
            $admin = new User();
            $admin->username = $this->admin_username;
            $admin->email = $this->admin_email;
            $admin->password = $this->admin_pass;
            $admin->name = 'Administrator';
            $admin->role = 'admin';
            $admin->status = User::STATUS_ACTIVE;
            $admin->save(false);

            $systemLanguage = new Language();
            $systemLanguage->name = $this->language;
            $systemLanguage->title = self::$languageCodes[$this->language];
            $systemLanguage->status = Language::STATUS_SYSTEM;
            $systemLanguage->save(false);

            // save modules config file
            Module::saveModulesConfig();
            return true;
        } else
            return false;

    }
}
