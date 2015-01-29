<?php

/**
 * This is the model class for table "{{widget}}".
 *
 * The followings are the available columns in table '{{widget}}':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $content
 * @property string $description
 * @property string $version
 * @property string $author_name
 * @property array $author
 * @property array $depends
 * @property string $author_url
 * @property string $author_email
 * @property string $options
 * @property integer $status
 * @property integer $parent_id
 * @property integer $type
 * @property integer $is_cached
 * @property integer $cache_time
 * @property integer $order
 * @property string $position
 * @property string $layout
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property integer $item_id
 * @property integer $content_type
 */
class Widget extends CActiveRecord
{
    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    const TYPE_SYSTEM = 1;
    const TYPE_USER_HTML = 2;
    const TYPE_USER_PHP = 3;

    public $author;
    public $depends;




	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Widget the static model class
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
		return '{{widget}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, title', 'required'),
            array('status, parent_id, type, is_cached, cache_time, order, item_id, content_type', 'numerical', 'integerOnly'=>true),
			array('name, title, author_name, author_url, author_email, version', 'length', 'max'=>125),
			array('description', 'length', 'max'=>255),
			array('version', 'length', 'max'=>45),
			array('version', 'checkDepends'),
            array('content', 'required', 'on'=>'UserWidget'),
			array('options, description, author, depends, parent_id, content, position, layout, module, controller, action, item_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, title, description, version, author_name, author_url, author_email, options, position, status', 'safe', 'on'=>'search'),

		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'parent'=>array(self::BELONGS_TO, 'Widget', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'name' => Yii::t('core/admin','Name'),
            'title' => Yii::t('core/admin','Title'),
            'description' => Yii::t('core/admin','Description'),
            'content'=>Yii::t('core/admin','Content'),
            'version' => Yii::t('core/admin','Version'),
            'options' => Yii::t('core/admin','Options'),
            'author' => Yii::t('core/admin','Author'),
            'author_name'=>Yii::t('core/admin','Author name'),
            'author_email'=>Yii::t('core/admin','Author E-mail'),
            'author_url'=>Yii::t('core/admin','Author link'),
            'status' => Yii::t('core/admin','Status'),
            'parent_id' => Yii::t('core/admin', 'Parent'),
            'type' => Yii::t('core/admin','Type'),
            'is_cached' => Yii::t('core/admin','Cache'),
            'cache_time' => Yii::t('core/admin','Cache time'),
            'order' => Yii::t('core/admin','Order'),
            'position' => Yii::t('core/admin','Position'),
            'layout' => Yii::t('core/admin','Layout'),
            'module' => Yii::t('core/admin','Module'),
            'controller' => Yii::t('core/admin','Controller'),
            'action' => Yii::t('core/admin','Action'),
            'item_id' => Yii::t('core/admin','Item ID'),
            'content_type' => Yii::t('core/admin','Turn on editor'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
        $criteria->compare('content',$this->content,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('author_name',$this->author_name,true);
		$criteria->compare('author_url',$this->author_url,true);
		$criteria->compare('author_email',$this->author_email,true);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('status',$this->status);
        $criteria->compare('parent_id',$this->parent_id);
        $criteria->compare('type',$this->type);
        $criteria->compare('is_cached',$this->is_cached);
        $criteria->compare('cache_time',$this->cache_time);
        $criteria->compare('order',$this->order);
        $criteria->compare('position',$this->position,true);
        $criteria->compare('layout',$this->layout,true);
        $criteria->compare('module',$this->module,true);
        $criteria->compare('controller',$this->controller,true);
        $criteria->compare('action',$this->action,true);
        $criteria->compare('item_id',$this->item_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$this->ml->modifySearchCriteria($criteria),
        ));
	}

    public function behaviors()
    {
        return array(
            'ml' => array(
                'class' => 'application.components.MultilingualBehavior',
                'localizedAttributes' => array(
                    'title', 'content', 'description'
                ),
                'langClassName' => 'WidgetLang',
                'langTableName' => 'widget_lang',
                'langField' => 'lang_id',
                'localizedPrefix' => 'l_',
                'languages' => Language::getListLanguages(),
                'defaultLanguage' => Language::getDefaultLanguage()->name,
                'langForeignKey' => 'widget_id',
                'dynamicLangClass' => true,
            ),
        );
    }

    public function defaultScope()
    {
        return $this->ml->localizedCriteria();
    }

    public function getStatusName()
    {
        if ($this->status == self::STATUS_DISABLE)
            return 'Не доступный';
        else
            return 'Доступный';
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

    public function afterFind()
    {
        parent::afterFind();
        $this->options = CJavaScript::jsonDecode($this->options);
    }

     /**
     * Return array of widgets for install or upgrade
     * @return CArrayDataProvider
     */
    public static function widgetsForInstall()
    {
        // get all installed widgets
        $widgetsList = self::model()->findAll();
        foreach ($widgetsList as $widget)
        {
            $widgetsInstalled[$widget->name] = $widget->version;
        }

        // get all folders in widgets
        $widgetsFolderList = glob(YiiBase::getPathOfAlias('application.widgets').DIRECTORY_SEPARATOR."*",GLOB_ONLYDIR);
        $widgetsArray = array();
        foreach ($widgetsFolderList as $widgetFolder)
        {
            if (is_file($widgetFolder.DIRECTORY_SEPARATOR.'info.php'))
            {
                $widgetItem = require($widgetFolder.DIRECTORY_SEPARATOR.'info.php');
                $widgetItem['id']='';
                if (empty($widgetsInstalled[$widgetItem['name']]))
                    $widgetsArray[] = $widgetItem;
                elseif ($widgetsInstalled[$widgetItem['name']]!=$widgetItem['version'])
                {
                    $widgetItem['need_upgrade'] = true;
                    $widgetsArray[] = $widgetItem;
                }
            }
        }
        return new CArrayDataProvider($widgetsArray);
    }

    public function getAttributesNames()
    {
        $file = YiiBase::getPathOfAlias('application.widgets.'.strtolower(substr($this->name,0,1)).substr($this->name,1,strlen($this->name))).DIRECTORY_SEPARATOR."info.php";
        if (is_file($file))
        {
            $arrayInfo = require($file);
            return $arrayInfo['editableAttributes'];
        }
        else
            return true;
    }

    public static function install($widget) {
        if (!empty($widget)&&is_file(YiiBase::getPathOfAlias('application.widgets.'.strtolower(substr($widget,0,1)).substr($widget,1,strlen($widget))).DIRECTORY_SEPARATOR."info.php"))
        {
            // new widget model
            $model = new self();
            /**
             * @var Widget $model
             */
            $model->name = $widget;
            $model->attributes = require(YiiBase::getPathOfAlias('application.widgets.'.strtolower(substr($widget,0,1)).substr($widget,1,strlen($widget))).DIRECTORY_SEPARATOR."info.php");

            // Save model of module
            $model->type = self::TYPE_SYSTEM;
            if ($model->save())
                return true;
            else
                return $model->errors;
        } else
            return false;
    }

    public function checkDepends($attributes, $params)
    {
        if ($this->parent_id)
            return true;
        if (empty($this->depends))
            return true;
        else {
            if (isset($this->depends['modules'])&&is_array($this->depends['modules']))
            {
                foreach ($this->depends['modules'] as $module=>$options)
                {
                    if (Yii::app()->getModule(strtolower($module))===null)
                    {
                        $this->addError('version', Yii::t('core/admin', 'For installation the widget needs module').' "'.ucfirst($module).'" '.Yii::t('core/admin', 'with version is not below').' '.$options['version']);
                    } else {
                        $basePatchModule = Yii::app()->getModule(strtolower($module))->basePath;
                        $info = require($basePatchModule.DIRECTORY_SEPARATOR.'info.php');
                        if (version_compare($info['version'],$options['version'],'<')) {
                            $this->addError('version', Yii::t('core/admin', 'For installation the widget needs module').' "'.ucfirst($module).'" '.Yii::t('core/admin', 'with version is not below').' '.$options['version']);
                        }
                    }
                }
            }
        }
    }

    public function __clone()
    {
        unset($this->id);
        $this->isNewRecord = true;
        if ($this->type == self::TYPE_SYSTEM) {
            $info = require(YiiBase::getPathOfAlias('application.widgets.'.strtolower(substr($this->name,0,1)).substr($this->name,1,strlen($this->name))).DIRECTORY_SEPARATOR."info.php");
            $this->attributes = $info;
        }
    }

    public static function listLayouts()
    {
        $layouts = CFileHelper::findFiles(realpath(
            Yii::app()->basePath . DIRECTORY_SEPARATOR .
                'views' .DIRECTORY_SEPARATOR.
                'layouts'.DIRECTORY_SEPARATOR.'widgets'), array('level'=>0));
        $themeLayouts = CFileHelper::findFiles(realpath(
            Yii::app()->theme->basePath . DIRECTORY_SEPARATOR .
                'views' .DIRECTORY_SEPARATOR.
                'layouts'.DIRECTORY_SEPARATOR.'widgets'), array('level'=>0));
        foreach ($themeLayouts as $themeLayout) {
            $layouts[] = $themeLayout;
        }
        $resultList = array();
        foreach($layouts as $layout) {
            $filename = basename($layout, '.php');
            $resultList[$filename] = $filename;
        }
        return $resultList;
    }

    public static function prepareErrorMessage($modelErrors)
    {
        $errorMessages = array();
        foreach ($modelErrors as $attribute=>$messages) {
            foreach ($messages as $message)
                $errorMessages[] = $message;
        }
        return implode('<br/>', $errorMessages);
    }
}