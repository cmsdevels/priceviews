<?php

/**
 * This is the model class for table "{{route}}".
 *
 * The followings are the available columns in table '{{route}}':
 * @property integer $id
 * @property string $name
 * @property string $current_name
 * @property string $title
 * @property string $module
 * @property string $model
 * @property string $admin_menu
 * @property integer $item_id
 * @property string $url
 * @property string $full_url
 * @property integer $lang_id
 * @property integer $status
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property integer $status_model
 *
 * The followings are the available model relations:
 * @property Language $lang
 */
class Route extends CActiveRecord
{
    const NOT_CAN_DELETE = 0;
    const CAN_DELETE = 1;

    const CAN_HAVE_CHILD = 1;
    const NOT_CAN_HAVE_CHILD = 0;

    const STATUS_ENABLE_MOVE = 0;
    const STATUS_DISABLE_MOVE = 1;

    const STATUS_MODEL_NO_PUBLISHED = 0;
    const STATUS_MODEL_PUBLISHED = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Route the static model class
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
		return '{{route}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, title, url, full_url', 'required'),
			array('item_id, lang_id, status, level, status_model', 'numerical', 'integerOnly'=>true),
			array('name, title, module, model, url, full_url', 'length', 'max'=>255),
            array('admin_menu, current_name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, title, module, model, item_id, url, full_url, lang_id, status', 'safe', 'on'=>'search'),
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
			'lang' => array(self::BELONGS_TO, 'Language', 'lang_id'),
		);
	}

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->admin_menu = CJSON::encode($this->admin_menu);
            $i=0;
            while (self::model()->exists("url = '".$this->url."'".($this->isNewRecord ? "" : " AND id != ".$this->id))) {
                $i++;
                $this->url = $this->url.$i;
            }
            $i=0;
            while (self::model()->exists("full_url = '".$this->full_url."'".($this->isNewRecord ? "" : " AND id != ".$this->id))) {
                $i++;
                $this->full_url = $this->full_url.$i;
            }
            return true;
        } else
            return false;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->scenario != 'deleteBaseObject') {
                if (!empty($this->module))
                    Yii::import('application.modules.'.$this->module.'.models.*');
                $model = CActiveRecord::model($this->model)->findByPk($this->item_id);
                if ($model !== null) {
                    $model->scenario = 'deleteRoute';
                    if (method_exists($model, 'deleteNode'))
                        $model->deleteNode();
                    else
                        $model->delete();
                }
            }
            $menuItems = Menu::model()->findAllByAttributes(array(
                'route_id'=>$this->id
            ));
            foreach ($menuItems as $menuItem)
                /**
                 * @var $menuItem Menu|NestedSetBehavior
                 */
                $menuItem->deleteNode();
            return true;
        } else
            return false;
    }

    public function afterSave()
    {
        parent::afterSave();
        Yii::app()->cache->set($this->name.'_'.$this->item_id, $this->full_url);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->admin_menu = CJSON::decode($this->admin_menu);
    }



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'current_name' => 'Current name',
			'title' => 'Title',
			'module' => 'Module',
			'model' => 'Model',
			'item_id' => 'Item',
			'url' => 'Url',
			'full_url' => 'Full Url',
			'lang_id' => 'Lang',
			'status' => 'Status',
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
		$criteria->compare('module',$this->module,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('full_url',$this->full_url,true);
		$criteria->compare('lang_id',$this->lang_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function behaviors()
    {
        return array(
            'nestedSetBehavior'=>array(
                'class'=>'application.extensions.yiiext-nested-set-behavior.NestedSetBehavior',
                'leftAttribute'=>'lft',
                'rightAttribute'=>'rgt',
                'levelAttribute'=>'level',
                'rootAttribute'=>'root',
                'hasManyRoots'=>true

            ),
        );
    }

    public static function getItem($model, $item_id, $module = null)
    {
        return self::model()->findByAttributes(array(
            'model'=>$model,
            'item_id'=>$item_id,
            'module'=>$module
        ));
    }

    protected function getCurrentUrl()
    {
        $options = $this->admin_menu;
        if (is_string($options))
            $options = CJSON::decode($options);
        if (!isset($options['additionalParent']))
            return $this->url;
        $additionalParentRoute = self::model()->findByAttributes(array(
            'model'=>$options['additionalParent']['model'],
            'module'=>$options['additionalParent']['module'],
            'item_id'=>$options['additionalParent']['item_id']
        ));
        if ($additionalParentRoute !== null)
            return $additionalParentRoute->full_url.'/'.$this->url;
        else
            return $this->url;
    }

    public function updateUrl()
    {
        if (is_string($this->admin_menu))
            $this->admin_menu = CJSON::decode($this->admin_menu);
        $prefix = (isset($this->admin_menu['additionalUrlPrefix']) && !empty($this->admin_menu['additionalUrlPrefix'])) ? $this->admin_menu['additionalUrlPrefix'].'/' : '' ;
        if ($this->parent !== null) {
            $this->full_url = $prefix.$this->parent->full_url.'/'.$this->getCurrentUrl();
            if ($this->saveNode()) {
                foreach ($this->children()->findAll() as $child)
                    $child->updateUrl();
            }
        } else {
            $this->full_url = $prefix.$this->getCurrentUrl();
            if ($this->saveNode()) {
                foreach ($this->children()->findAll() as $child)
                    $child->updateUrl();
            }
        }
    }

    public function getHaveChild()
    {
        if ($this->status == self::STATUS_DISABLE_MOVE)
            return 'cannot_move';
        if ($this->admin_menu['can_have_child'] == self::CAN_HAVE_CHILD)
            return 'can_have_child';
        else
            return 'cannot_have_child';
    }

    public static function getFullUrl($route, $id)
    {
        $fullUrl = Yii::app()->cache->get('/'.$route.'_'.$id);
        if ($fullUrl === false) {
            $route = self::model()->findByAttributes(array(
                'item_id'=>$id,
                'name'=>'/'.$route
            ));
            if ($route !== null) {
                $fullUrl = $route->full_url;
                Yii::app()->cache->set($route->name.'_'.$route->item_id, $route->full_url);
            }
        }
        return $fullUrl;
    }

    public function getAdminItemUrl($action = 'update')
    {
        $route = '/';
        if (!empty($this->module))
            $route .= $this->module.'/';
        $route .= 'admin/'.lcfirst($this->model).'/'.$action;
        return Yii::app()->controller->createUrl($route, array('id'=>$this->item_id));
    }

    public function getViewItemUrl()
    {
        $this->updateUrl();
        return Yii::app()->controller->createUrl($this->name, array('id'=>$this->item_id));
    }
}