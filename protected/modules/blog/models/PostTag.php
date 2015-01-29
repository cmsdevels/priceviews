<?php

/**
 * This is the model class for table "{{post_tag}}".
 *
 * The followings are the available columns in table '{{post_tag}}':
 * @property string $name
 * @property integer $frequency
 */
class PostTag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PostTag the static model class
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
		return '{{post_tag}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('frequency', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, frequency', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'frequency' => 'Frequency',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('frequency',$this->frequency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function string2array($tags)
    {
        $tags = explode(',', $tags);
        for ($i = 0; $i<count($tags); $i++) {
            $tags[$i] = trim($tags[$i]);
        }
        return $tags;
    }

    public static function updateFrequency($oldTags, $newTags)
    {
        $oldTags = self::string2array($oldTags);
        $newTags = self::string2array($newTags);
        self::addTags(array_values(array_diff($newTags, $oldTags)));
        self::removeTags(array_values(array_diff($oldTags, $newTags)));
    }

    public static function addTags($tags)
    {
        if (empty($tags)) {
            return;
        }
        $criteria = new CDbCriteria();
        foreach ($tags as $tag) {
            $criteria->addCondition("LOWER(name) = '".strtolower($tag)."'",'OR');
        }
        self::model()->updateCounters(array('frequency'=>1),$criteria);
        foreach($tags as $name) {
            if(!self::model()->exists('LOWER(name)=:name',array(':name'=>strtolower($name)))) {
                $tag=new PostTag;
                $tag->name=$name;
                $tag->frequency=1;
                $tag->save();
            }
        }
    }

    public static function removeTags($tags)
    {
        if(empty($tags))
            return;
        $criteria=new CDbCriteria;
        foreach ($tags as $tag) {
            $criteria->addCondition("LOWER(name) = '".strtolower($tag)."'",'OR');
        }
        self::model()->updateCounters(array('frequency'=>-1),$criteria);
        self::model()->deleteAll('frequency<=0');
    }
}