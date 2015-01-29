<?php

/**
 * This is the model class for table "{{params}}".
 *
 * The followings are the available columns in table '{{params}}':
 * @property integer $id
 * @property string $key
 * @property string $desc
 * @property integer $type
 */
class Params extends CActiveRecord
{
    const TYPE_PLAIN_TEXT = 0;
    const TYPE_PASSWORD = 1;
    const TYPE_IMAGE = 2;
    const TYPE_TEXT_AREA = 3;
    const TYPE_TEXT_EDITOR = 4;
    const TYPE_FILE = 5;
    const TYPE_EMAIL = 6;
    const TYPE_URL = 7;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Params the static model class
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
		return '{{params}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key, desc, type', 'required'),
			array('key, desc', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, key, desc, type', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'key' => Yii::t('core/admin', 'Param'),
			'desc' => Yii::t('core/admin','Description'),
            'type' => Yii::t('core/admin','Type'),
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
		$criteria->compare('key',$this->key,true);
		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getFilePath($file)
    {
        if (is_file(Yii::getPathOfAlias('webroot').'/upload/settings/'.$file)) {
            return Yii::app()->request->hostInfo.'/upload/settings/'.$file;
        } else
            return false;
    }

    public static function typesList()
    {
        return array (
            self::TYPE_PLAIN_TEXT => Yii::t('core/admin','Plain Text'),
            self::TYPE_PASSWORD => Yii::t('core/admin','Password'),
            self::TYPE_IMAGE => Yii::t('core/admin','Image'),
            self::TYPE_TEXT_AREA => Yii::t('core/admin','Text Area'),
            self::TYPE_TEXT_EDITOR => Yii::t('core/admin','Text Editor'),
            self::TYPE_FILE => Yii::t('core/admin','File'),
            self::TYPE_EMAIL => Yii::t('core/admin','Email'),
            self::TYPE_URL => Yii::t('core/admin','Url'),
        );
    }

    public function getTypeName($type = null)
    {
        $typesList = self::typesList();
        if ($type === null)
            $type = $this->type;

        return isset($typesList[$type]) ? $typesList[$type] : 'N/A';
    }
}