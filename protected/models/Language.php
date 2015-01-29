<?php

/**
 * This is the model class for table "{{language}}".
 *
 * The followings are the available columns in table '{{language}}':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $status
 */
class Language extends CActiveRecord
{
    const STATUS_NO_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_SYSTEM = 2;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Language the static model class
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
		return '{{language}}';
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
			array('name, title', 'unique'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>2),
			array('title', 'length', 'max'=>125),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, title, status', 'safe', 'on'=>'search'),
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
			'status' => Yii::t('core/admin','Status'),
		);
	}

    public function getStatusText()
    {
        switch($this->status) {
            case self::STATUS_NO_PUBLISHED:
                return Yii::t('core/admin','Disable');
                break;
            case self::STATUS_PUBLISHED:
                return Yii::t('core/admin','Enable');
                break;
            case self::STATUS_SYSTEM:
                return Yii::t('core/admin','System language');
                break;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->status != self::STATUS_SYSTEM)
                return true;
            else
                return false;
        } else
            return false;
    }

    public function afterSave()
    {
        parent::afterSave();
        Yii::app()->cache->delete('listLanguages');
        Yii::app()->cache->delete('defaultLanguage');
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::app()->cache->delete('listLanguages');
        Yii::app()->cache->delete('defaultLanguage');
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
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function systemId()
    {
        return self::model()->findByAttributes(array('status'=>self::STATUS_SYSTEM))->id;
    }

    public static function getListLanguages()
    {
        $data = Yii::app()->cache->get('listLanguages');
        if ($data === false) {
            $models = self::model()->findAll(array('order'=>'status DESC'));
            $data = CHtml::listData($models, 'name', 'title');
            Yii::app()->cache->set('listLanguages', $data);
        }
        return $data;
    }

    public static function getDefaultLanguage()
    {
        $model = Yii::app()->cache->get('defaultLanguage');
        if ($model === false) {
            $model = self::model()->findByAttributes(array(
                'status'=>self::STATUS_SYSTEM
            ));
            if ($model === null)
                $model = self::model()->find();
            Yii::app()->cache->set('defaultLanguage', $model);
        }
        return $model;
    }
}