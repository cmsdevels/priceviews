<?php

/**
 * This is the model class for table "{{offer_items}}".
 *
 * The followings are the available columns in table '{{offer_items}}':
 * @property integer $id
 * @property integer $offer_id
 * @property string $create_date
 * @property integer $status
 * @property string $title
 * @property string $description
 * @property string $price
 * @property string $link
 * @property string $image
 * @property string $offer_logo
 *
 * The followings are the available model relations:
 * @property Offer $offer
 */
class OfferItems extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OfferItems the static model class
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
		return '{{offer_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offer_id, create_date, status, title, price, link, image, offer_logo', 'required'),
			array('offer_id, status', 'numerical', 'integerOnly'=>true),
			array('title, price, link, image, offer_logo', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offer_id, create_date, status, title, description, price, link, image, offer_logo', 'safe', 'on'=>'search'),
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
			'offer' => array(self::BELONGS_TO, 'Offer', 'offer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'offer_id' => Yii::t('app','Offer'),
			'create_date' => Yii::t('app','Create Date'),
			'status' => Yii::t('app','Status'),
			'title' => Yii::t('app','Title'),
			'description' => Yii::t('app','Description'),
			'price' => Yii::t('app','Price'),
			'link' => Yii::t('app','Link'),
			'image' => Yii::t('app','Image'),
			'offer_logo' => Yii::t('app','Offer Logo'),
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
		$criteria->compare('offer_id',$this->offer_id);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('offer_logo',$this->offer_logo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));

        // return new CActiveDataProvider($this, array(
        //     'criteria'=>$this->ml->modifySearchCriteria($criteria),
        // ));
	}

    public function behaviors()
    {
        return array(
//            'ml' => array(
//                'class' => 'application.components.MultilingualBehavior',
//                'localizedAttributes' => array(
//
//                ),
//                'langClassName' => 'OfferItemsLang',
//                'langTableName' => 'offeritems_lang',
//                'langField' => 'lang_id',
//                'localizedPrefix' => 'l_',
//                'languages' => Language::getListLanguages(),
//                'defaultLanguage' => Language::getDefaultLanguage()->name,
//                'langForeignKey' => 'offeritems_id',
//                'dynamicLangClass' => true,
//            ),
//            'RouteBehavior'=>array(
//                'class'=>'application.components.cms.CmsRouteBehavior',
//                'route'=>'/offeritems/view',
//                'options'=>array(
//                    'can_have_child'=>1,
//                    'is_clone'=>0,
//                    'can_delete'=>1,
//                    'create_child_item_link'=>array(
//                        'route'=>'/admin/offeritems/create',
//                        'param'=>'id',
//                        'paramName'=>'offeritems_id'
//                    ),
//                    'names'=>array(
//                        '/offeritems/view'=>'Просмотр'
//                    ),
//                ),
//            ),
        );
    }

    public function defaultScope()
    {
        return array();
        // return $this->ml->localizedCriteria();
    }

    public function getActions() {

        $offer = Offer::model()->findByPk($this->id);
//print_r($offer); exit();
        return $offer->title;
    }
}