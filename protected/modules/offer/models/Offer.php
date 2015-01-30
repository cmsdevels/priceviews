<?php

/**
 * This is the model class for table "{{offer}}".
 *
 * The followings are the available columns in table '{{offer}}':
 * @property integer $id
 * @property string $create_date
 * @property integer $status
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $image
 *
 * The followings are the available model relations:
 * @property OfferItems[] $offerItems
 */
class Offer extends CActiveRecord
{
    const STATUS_NO_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static $listStatuses = array(
        self::STATUS_ACTIVE=>'Активно',
        self::STATUS_NO_ACTIVE=>'Не активно',
    );

    public function getStatusText()
    {
        return self::$listStatuses[$this->status];
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Offer the static model class
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
		return '{{offer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_date, status, title, image', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('title, image', 'length', 'max'=>255),
			array('description, content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, create_date, status, title, description, content, image', 'safe', 'on'=>'search'),
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
			'offerItems' => array(self::HAS_MANY, 'OfferItems', 'offer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','id'),
			'create_date' => Yii::t('app','Дата'),
			'status' => Yii::t('app','Статус'),
			'title' => Yii::t('app','Название'),
			'description' => Yii::t('app','Краткое описание'),
			'content' => Yii::t('app','Полное описание'),
			'image' => Yii::t('app','Изображение'),
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
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));

        // return new CActiveDataProvider($this, array(
        //     'criteria'=>$this->ml->modifySearchCriteria($criteria),
        // ));
	}


//    public function beforeSave()
//    {
//        if (parent::beforeSave()) {
//            $this->create_date = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->create_date);
//        }
//    }

    public function behaviors()
    {
        return array(
//            'ml' => array(
//                'class' => 'application.components.MultilingualBehavior',
//                'localizedAttributes' => array(
//
//                ),
//                'langClassName' => 'OfferLang',
//                'langTableName' => 'offer_lang',
//                'langField' => 'lang_id',
//                'localizedPrefix' => 'l_',
//                'languages' => Language::getListLanguages(),
//                'defaultLanguage' => Language::getDefaultLanguage()->name,
//                'langForeignKey' => 'offer_id',
//                'dynamicLangClass' => true,
//            ),




            'RouteBehavior'=>array(
                'class'=>'application.components.cms.CmsRouteBehavior',
                'route'=>'/offer/view',
                'options'=>array(
                    'can_have_child'=>1,
                    'is_clone'=>0,
                    'can_delete'=>1,
                    'create_child_item_link'=>array(
                        'route'=>'/admin/offer/create',
                        'param'=>'id',
                        'paramName'=>'offer_id'
                    ),
                    'names'=>array(
                        '/offer/view'=>'Просмотр'
                    ),
                ),
            ),
            'MetaDataBehavior'=>array(
                'class'=>'application.components.MetaDataBehavior'
            ),
        );
    }

    public function defaultScope()
    {
        return array();
//         return $this->ml->localizedCriteria();
    }

    public function getDate(){
        return Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->create_date);
    }

    public function getStatusName()
    {
        if ($this->status == self::STATUS_NO_ACTIVE)
            return 'Не активный';
        else
            return 'Активный';
    }
}