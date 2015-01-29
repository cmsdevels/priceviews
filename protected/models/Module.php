<?php

/**
 * This is the model class for table "{{module}}".
 *
 * The followings are the available columns in table '{{module}}':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $options
 * @property string $version
 * @property array  $author
 * @property string $author_name
 * @property string $author_url
 * @property string $author_email
 * @property string $admin_controller
 * @property integer $status
 */
class Module extends CActiveRecord
{
    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    public $author;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Module the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{module}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, version, author_name, author_url, author_email', 'required'),
			array('name', 'unique'),
			array('author_email', 'email'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('title, name, admin_controller', 'length', 'max'=>125),
            array('options, author', 'safe'),
			array('description', 'length', 'max'=>255),
			array('version', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, name, description, version, author, status', 'safe', 'on'=>'search'),
		);
	}

    public function beforeValidate()
    {
        if (parent::beforeValidate())
        {
            if ($this->isNewRecord) {
                $this->author_name = $this->author['name'];
                $this->author_url = $this->author['url'];
                $this->author_email = $this->author['email'];
            }
            $this->options = CJavaScript::jsonEncode($this->options);
            return true;
        } else
            return false;
    }

    public function afterSave()
    {
        parent::afterSave();
        self::saveModulesConfig();
        AuthItemForm::getModuleSpecs($this);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        self::saveModulesConfig();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->options = CJavaScript::jsonDecode($this->options);
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('core/admin','ID'),
			'name' => Yii::t('core/admin','Name'),
			'title' => Yii::t('core/admin','Title'),
			'description' => Yii::t('core/admin','Description'),
			'version' => Yii::t('core/admin','Version'),
			'options' => Yii::t('core/admin','Options'),
			'author' => Yii::t('core/admin','Author'),
            'author_name'=>Yii::t('core/admin','Author name'),
            'author_email'=>Yii::t('core/admin','Author E-mail'),
            'author_url'=>Yii::t('core/admin','Author link'),
			'status' => Yii::t('core/admin','Status'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Save modules config file with all enabled modules and them config
     */
    public static function saveModulesConfig()
    {
        $configFile = Yii::app()->basePath.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."modules.php";

        $models = self::model()->findAll();

        $configArray = array();

        foreach ($models as $model)
        {
            $moduleInfo = require(Yii::app()->basePath.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$model->name.DIRECTORY_SEPARATOR.'info.php');
            if (isset($moduleInfo['options'])&&is_array($moduleInfo['options']))
                $model->options = CMap::mergeArray($moduleInfo['options'], $model->options);
            $configArray[strtolower($model->name)] = $model->options;
        }

        $configString = "<?php\n return " . var_export($configArray, true) . " ;\n?>";

        $config = fopen($configFile, 'w+');
        if ($config)
        {
            fwrite($config, $configString);

            fclose($config);

            @chmod($configFile, 0666);
        }

    }

    public function getAttributesNames()
    {
        $module = Yii::app()->getModule(strtolower($this->title));
        if (method_exists($module, 'getEditableAttributes'))
            return $module->editableAttributes;
        else
        {
            $file = YiiBase::getPathOfAlias('application.modules.'.strtolower($this->name)).DIRECTORY_SEPARATOR."info.php";
            if (is_file($file))
            {
                $arrayInfo = require($file);
                return $arrayInfo['editableAttributes'];
            }
            else
                return true;
        }
    }

    protected static function getModulesList()
    {
        $updateServer = trim(Yii::app()->params['updateServer'],'/');
        $fullUpdateServerUrl = $updateServer.'/update/getModules';
        $listModulesJson = self::getListModulesApi($fullUpdateServerUrl);
        return CJavaScript::jsonDecode($listModulesJson);
    }

    /**
     * Return array of modules for install or upgrade
     * @return CArrayDataProvider
     */
    public static function modulesForInstall()
    {
        // get all installed modules
        $modulesList = self::model()->findAll();
        foreach ($modulesList as $module)
        {
            $modulesInstalled[$module->title] = $module->version;
        }

        // get all folders in modules
        $modulesForInstallList = self::getModulesList();
        if($modulesForInstallList===false || !is_array($modulesForInstallList)){
            Yii::app()->user->setFlash('errorServer',Yii::t('core/admin','Temporary server error. Please try again later.'));
            return false;
        }
        $modulesArray = array();
        foreach ($modulesForInstallList as $moduleForInstall)
        {
            $moduleItem = $moduleForInstall;
//            $moduleItem['id']='';
            if (empty($modulesInstalled[$moduleItem['title']]))
                $modulesArray[] = $moduleItem;
            elseif ($modulesInstalled[$moduleItem['title']]!=$moduleItem['version'])
            {
                $moduleItem['need_upgrade'] = true;
                $modulesArray[] = $moduleItem;
            }
        }
        return new CArrayDataProvider($modulesArray);
    }

    protected static function downloadModuleArchive($module, $id=null)
    {
        $updateServer = trim(Yii::app()->params['updateServer'],'/');
        $moduleArchive = file_get_contents(
            $updateServer.'/update/getModuleArchiveLink?name='.strtolower($module).
            '&hostInfo='.str_replace('http://', '', str_replace('https://', '', Yii::app()->request->hostInfo)).
            ($id !== null ? '&id='.$id : '')
        );
        if ($moduleArchive !== false && !empty($moduleArchive)) {
            $tempName = uniqid().'.zip';
            $dirUpdates = YiiBase::getPathOfAlias('webroot.updates');
            $dirArchives = YiiBase::getPathOfAlias('webroot.updates.archives');
            if(!is_dir($dirUpdates)){
                mkdir($dirUpdates);
                if(!is_dir($dirArchives)){
                    mkdir($dirArchives);
                }
            }
            if (file_put_contents($dirArchives.DIRECTORY_SEPARATOR.$tempName, file_get_contents($moduleArchive)) !== false)
                return $tempName;
        }
        return false;

    }

    protected static function extractModuleArchive($tempName)
    {
        $zip = new ZipArchive;
        if ($zip->open(YiiBase::getPathOfAlias('webroot.updates.archives').DIRECTORY_SEPARATOR.$tempName) === TRUE) {
            $zip->extractTo(YiiBase::getPathOfAlias('webroot.updates.modules').DIRECTORY_SEPARATOR);
            $zip->close();
            @unlink(YiiBase::getPathOfAlias('webroot.updates.archives').DIRECTORY_SEPARATOR.$tempName);
            return true;
        }
        @unlink(YiiBase::getPathOfAlias('webroot.updates.archives').DIRECTORY_SEPARATOR.$tempName);
        return false;
    }


    public static function install($module, $archiveName=null, $id=null)
    {
        $archiveModuleName = false;
        if ($archiveName !== null)
            $archiveModuleName = $archiveName;
        else
            $archiveModuleName = self::downloadModuleArchive($module, $id);
        if (!empty($module) && $archiveModuleName !==false && self::extractModuleArchive($archiveModuleName) && is_file(YiiBase::getPathOfAlias('webroot.updates.modules.'.$module).DIRECTORY_SEPARATOR."info.php"))
        {
            // Copy module files to application
            CFileHelper::copyDirectory(
                YiiBase::getPathOfAlias('webroot.updates.modules.'.$module),
                YiiBase::getPathOfAlias('application.modules.'.$module)
            );

            // load module
            if (is_file(YiiBase::getPathOfAlias('application.modules.'.$module).DIRECTORY_SEPARATOR.ucfirst($module)."Module.php"))
                Yii::app()->setModules(array($module));

            // new module model
            $model = new self();
            $model->name = $module;
            $model->attributes = require(YiiBase::getPathOfAlias('application.modules.'.$module).DIRECTORY_SEPARATOR."info.php");

            if (method_exists(Yii::app()->getModule($module), 'install')&&Yii::app()->getModule($module)->install()===false) {
                return false;
            }
            self::rrmdir(YiiBase::getPathOfAlias('webroot.updates.modules.'.$module));
            // Save model of module
            return $model->save();
        }
        return false;
    }

    public static function upgrade($module, $id=null)
    {

        if (!empty($module) && self::extractModuleArchive($module) && is_file(YiiBase::getPathOfAlias('webroot.updates.modules.'.$module).DIRECTORY_SEPARATOR."info.php"))
        {
            // Delete old files
            self::rrmdir(YiiBase::getPathOfAlias('application.modules.'.$module));
            // Copy module files to application
            CFileHelper::copyDirectory(
                YiiBase::getPathOfAlias('webroot.updates.modules.'.$module),
                YiiBase::getPathOfAlias('application.modules.'.$module)
            );

            // load module
            if (is_file(YiiBase::getPathOfAlias('application.modules.'.$module).DIRECTORY_SEPARATOR.ucfirst($module)."Module.php"))
                Yii::app()->setModules(array($module));

            // new module model
            $model = self::model()->findByAttributes(array('name'=>$module));

            $new_configs = require(YiiBase::getPathOfAlias('application.modules.'.$module).DIRECTORY_SEPARATOR."info.php");
            $model->attributes = CMap::mergeArray($new_configs, $model->attributes);
            $model->version = $new_configs['version'];

            if (method_exists(Yii::app()->getModule($module), 'install')&&Yii::app()->getModule($module)->install()===false) {
                return false;
            }
            self::rrmdir(YiiBase::getPathOfAlias('webroot.updates.modules.'.$module));
            // Save model of module
            return $model->save();
        }
        return false;
    }

    public static function listControllers($module)
    {
        $files = array();
        if ($module) {
            if (!file_exists(realpath(Yii::app()->basePath . DIRECTORY_SEPARATOR .'modules' .DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'controllers')))
                $files = array();
            else {
                $files = CFileHelper::findFiles(realpath(
                    Yii::app()->basePath . DIRECTORY_SEPARATOR .
                    'modules' .DIRECTORY_SEPARATOR.
                    $module.DIRECTORY_SEPARATOR.'controllers'), array('level'=>0));
            }
        } else {
            $files = CmsController::listBaseControllers();
        }
        $controllers = array();
        foreach($files as $file){
            $controllerName = basename($file, '.php');
            if ($module)
                Yii::import('application.modules.'.strtolower($module).'.controllers.'.$controllerName,true);
            else
                Yii::import('application.controllers.'.$controllerName,true);
            if( ($pos = strpos($controllerName, 'Controller')) > 0){
                $controller = new $controllerName($controllerName);
                $controllers[lcfirst(substr($controllerName, 0, $pos))] = $controller->getControllerName();
            }
        }
        return $controllers;
    }

    public static function listMethods($module, $controller)
    {
        if (!$controller)
            return array();
        $controllerName = ucfirst($controller).'Controller';
        if ($module)
            Yii::import('application.modules.'.strtolower($module).'.controllers.'.$controllerName,true);
        else
            Yii::import('application.controllers.'.$controllerName,true);
        $controllerObject = new $controllerName($controller);
        /**
         * @var CmsController $controllerObject
         */
        $specs = $controllerObject->accessSpecs();
        $methodsList = array();
        if (isset($specs['operations']))
            $methodsList = $specs['operations'];
        return $methodsList;
    }

    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function getRoutes($module = null)
    {
        $routes = array();
        if (empty($module)) {
            $modules = self::model()->findAll();
            foreach ($modules as $mod) {
                $routeFile = Yii::getPathOfAlias('application.modules.'.$mod->name).DIRECTORY_SEPARATOR.'route.php';
                if (is_file($routeFile)) {
                    $routesModule = require($routeFile);
                    foreach ($routesModule['rules'] as $url=>$route)
                        if (strpos($url, '<') === false)
                            $routes[$url]=$url;
                }
            }
        } else {
            $routeFile = Yii::getPathOfAlias('application.modules.'.$module).DIRECTORY_SEPARATOR.'route.php';
            if (is_file($routeFile)) {
                $routesModule = require($routeFile);
                foreach ($routesModule['rules'] as $url=>$route)
                    if (strpos($url, '<') === false)
                        $routes[$url]=$url;
            }
        }
        return $routes;
    }

    public function getImageUrl()
    {
        return Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('application.modules.'.$this->name).DIRECTORY_SEPARATOR.$this->name.'.png'
        );
    }

    public static function getListModulesApi($url)
    {
        if(function_exists('curl_version')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            $result = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($http_status == 200){
                return $result;
            }else{
                return false;
            }
        }else{
            return file_get_contents($url);
        }


    }

}