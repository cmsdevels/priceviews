<?php

/**
 * This is the model class for table "{{post_cat}}".
 *
 * The followings are the available columns in table '{{post_cat}}':
 * @property integer $id
 * @property string $name
 * @property string $seo_link
 * @property string $pub_date
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Post[] $posts
 */
class PostCat extends CActiveRecord
{
    const STATUS_NO_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PostCat the static model class
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
		return '{{post_cat}}';
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
			array('status', 'numerical', 'integerOnly'=>true),
			array('name, seo_link', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, seo_link, pub_date, status', 'safe', 'on'=>'search'),
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
			'posts' => array(self::HAS_MANY, 'Post', 'cat_id'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'seo_link' => 'Ссылка',
			'pub_date' => 'Дата добавления',
			'status' => 'Статус',
		);
	}

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->seo_link = UrlTranslit::translit($this->name);
            $i = 1;
            while (PostCat::model()->findByAttributes(array('seo_link'=>$this->seo_link), ($this->isNewRecord ? '' : "id != ".$this->id))!=null)
            {
                $this->seo_link = $this->seo_link.$i;
                $i++;
            }
            return true;
        } else
            return false;
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('seo_link',$this->seo_link,true);
		$criteria->compare('pub_date',$this->pub_date,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
            'criteria'=>$this->ml->modifySearchCriteria($criteria),
		));
	}

    public function scopes()
    {
        return array(
            'published'=>array(
                'condition'=>'status = '.self::STATUS_PUBLISHED
            ),
        );
    }

    public function getStatusName()
    {
        switch ($this->status) {
            case self::STATUS_NO_PUBLISHED:
                return "Не опубликовано";
                break;
            case self::STATUS_PUBLISHED:
                return "Опубликовано";
                break;
            default:
                return "Не опубликовано";
        }
    }

    public function behaviors()
    {
        return array(
            'ml' => array(
                'class' => 'application.components.MultilingualBehavior',
                'localizedAttributes' => array(
                    'name'
                ),
                'langClassName' => 'PostCatLang',
                'langTableName' => 'post_cat_lang',
                'langField' => 'lang_id',
                'localizedPrefix' => 'l_',
                'languages' => Language::getListLanguages(),
                'defaultLanguage' => Language::getDefaultLanguage()->name,
                'langForeignKey' => 'post_cat_id',
                'dynamicLangClass' => true,
            ),
            'RouteBehavior'=>array(
                'class'=>'application.components.cms.CmsRouteBehavior',
                'route'=>'/blog/postCat/view',
                'module'=>'blog',
                'titleAttr'=>'name',
                'options'=>array(
                    'can_have_child'=>1,
                    'is_clone'=>0,
                    'can_delete'=>1,
                    'create_child_item_link'=>array(
                        'route'=>'/blog/admin/post/create',
                        'param'=>'id',
                        'paramName'=>'category_id'
                    ),
                    'names'=>array(
                        '/blog/postCat/view'=>'Просмотр списка записей категории',
                        '/blog/postCat/viewPreview'=>'Просмотр списка записей категории с превью',
                        '/blog/postCat/viewSubCategories'=>'Просмотр подкатегорий',
                        '/blog/postCat/viewAllSub'=>'Просмотр подкатегорий и записей категории',
                    )
                ),
            ),

        );
    }

    public function defaultScope()
    {
        return $this->ml->localizedCriteria();
    }
}