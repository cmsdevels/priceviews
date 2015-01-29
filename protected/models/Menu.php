<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property integer $route_id
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property integer $status
 * @property integer $parent_id
 * @property integer $type
 * @property string $url
 */
class Menu extends CActiveRecord
{

    const STATUS_NO_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

    const TYPE_ROUTE = 0;
    const TYPE_LINK = 1;
    const TYPE_ROUTE_MODULE = 2;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
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
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('parent_id', 'required','on'=>'childItem'),
			array('route_id, status, parent_id, type', 'numerical', 'integerOnly'=>true),
			array('title, image', 'length', 'max'=>128),
			array('url', 'length', 'max'=>255),
            array('type', 'checkValue', 'on'=>'childItem'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, image, route_id, status', 'safe', 'on'=>'search'),
		);
	}

    public function checkValue($attributes, $params)
    {
        if ($this->type == self::TYPE_ROUTE && empty($this->route_id))
            $this->addError('type', Yii::t('core/admin', 'Select Route of site structure.'));
        if (($this->type == self::TYPE_LINK || $this->type == self::TYPE_ROUTE_MODULE) && empty($this->url))
            $this->addError('type', Yii::t('core/admin', 'Specify the url to a menu item.'));
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'route'=>array(self::BELONGS_TO, 'Route', 'route_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => Yii::t('core/admin', 'Title'),
			'image' => Yii::t('core/admin', 'Image'),
			'route_id' => Yii::t('core/admin', 'Route'),
			'root' => 'Root',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
			'status' => Yii::t('core/admin', 'Status'),
			'parent_id' => Yii::t('core/admin', 'Parent'),
			'type' => Yii::t('core/admin', 'Type'),
			'url' => Yii::t('core/admin', 'Url'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
    public function adminSearchMenu()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('title',$this->title,true);
        $criteria->compare('status',$this->status);
        $criteria->addCondition('parent_id IS NULL');

        return new CActiveDataProvider($this, array(
            'criteria'=>$this->ml->modifySearchCriteria($criteria),
        ));
    }



    public function getTitleList()
    {
        return str_repeat('- ',$this->level-1).$this->title;
    }

    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_ROUTE:
                return '/'.$this->route->full_url;
                break;
            case self::TYPE_LINK:
                return $this->url;
                break;
            case self::TYPE_ROUTE_MODULE:
                return $this->url;
                break;
        }

    }

    public function behaviors()
    {
        return array(
            'ml' => array(
                'class' => 'application.components.MultilingualBehavior',
                'localizedAttributes' => array(
                    'title'
                ),
                'langClassName' => 'MenuLang',
                'langTableName' => 'menu_lang',
                'langField' => 'lang_id',
                'localizedPrefix' => 'l_',
                'languages' => Language::getListLanguages(),
                'defaultLanguage' => Language::getDefaultLanguage()->name,
                'langForeignKey' => 'menu_id',
                'dynamicLangClass' => true,
                'createScenario'=>array('root', 'childItem')
            ),
            'nestedSetBehavior'=>array(
                'class'=>'application.extensions.yiiext-nested-set-behavior.NestedSetBehavior',
                'leftAttribute'=>'lft',
                'rightAttribute'=>'rgt',
                'levelAttribute'=>'level',
                'rootAttribute'=>'root',
                'hasManyRoots'=>true,

            ),
        );
    }

    public function defaultScope()
    {
        return $this->ml->localizedCriteria();
    }

    public static function getItems($menu_id)
    {
        $items = array();
        $menu = self::model()->findByPk($menu_id, 'status=:status', array(':status'=>self::STATUS_PUBLISHED));
        if ($menu !== null) {
            $menuItems = $menu->descendants()->findAll(array(
                'condition'=>'status=:status',
                'params'=>array(
                    ':status'=>self::STATUS_PUBLISHED
                )
            ));
            $new = array();
            foreach ($menuItems as $menuItem){
                $new[$menuItem->parent_id][] = $menuItem;
            }
            $items = self::RecursiveTree2($new, $menu_id);
        }

        return $items;
    }

    public static function RecursiveTree2(&$rs,$parent)
    {
        $out=array();
        if (!isset($rs[$parent]))
        {
            return $out;
        }
        foreach ($rs[$parent] as $row)
        {
            $children=self::RecursiveTree2($rs,$row->id);
            $node = array(
                'label'         => $row->title,
                'url'           => $row->getUrl(),
                'submenuOptions'=> array()
            );
            if ($children)
                $node['items']=$children;
            $out[]=$node;
        }
        return $out;
    }
    public function isMenuItems()
    {
        $result =Menu::model()->findAll(array('order'=>'root, lft'));
        if(empty($result)){
            return false;
        }else{
            return true;
        }
    }

    public function getMenuItemsListData(){

        $criteria = new CDbCriteria();
        $criteria->order = 'root, lft';
        if(!$this->isNewRecord){
            $criteria->addNotInCondition('t.id',array($this->id));
        }
        return CHtml::listData(Menu::model()->findAll($criteria), 'id', 'titleList');
    }
}