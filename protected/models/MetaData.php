<?php

/**
 * This is the model class for table "{{meta_data}}".
 *
 * The followings are the available columns in table '{{meta_data}}':
 * @property integer $id
 * @property string $model
 * @property integer $model_id
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 */
class MetaData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MetaData the static model class
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
		return '{{meta_data}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, model_id', 'required'),
			array('model_id', 'numerical', 'integerOnly'=>true),
			array('model', 'length', 'max'=>128),
			array('meta_title', 'length', 'max'=>255),
			array('meta_keywords, meta_description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, model, model_id, meta_title, meta_keywords, meta_description', 'safe', 'on'=>'search'),
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
			'model' => 'Model',
			'model_id' => 'Model',
			'meta_title' => 'СЕО Title',
			'meta_keywords' => 'СЕО Keywords',
			'meta_description' => 'СЕО Description',
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
		$criteria->compare('model',$this->model,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('meta_keywords',$this->meta_keywords,true);
		$criteria->compare('meta_description',$this->meta_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function behaviors()
    {
        return array(
            'ml' => array(
                'class' => 'application.components.MultilingualBehavior',
                'localizedAttributes' => array(
                    'meta_title', 'meta_keywords', 'meta_description'
                ),
                'langClassName' => 'MetaDataLang',
                'langTableName' => 'meta_data_lang',
                'langField' => 'lang_id',
                'localizedPrefix' => 'l_',
                'languages' => Language::getListLanguages(),
                'defaultLanguage' => Language::getDefaultLanguage()->name,
                'langForeignKey' => 'data_id',
                'dynamicLangClass' => true,
            ),
        );
    }

    public function defaultScope()
    {
        return $this->ml->localizedCriteria();
    }
}