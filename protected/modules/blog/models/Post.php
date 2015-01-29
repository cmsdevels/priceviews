<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property string $seo_link
 * @property string $pub_date
 * @property integer $status
 * @property integer $cat_id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property PostCat $cat
 * @property User $author
 */
class Post extends CmsActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $_oldTags;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
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
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, pub_date', 'required'),
			array('status, cat_id', 'numerical', 'integerOnly'=>true),
			array('title, seo_link', 'length', 'max'=>128),
			array('seo_link', 'unique'),
			array('pub_date', 'length', 'max'=>100),
			array('tags', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, tags, seo_link, pub_date, status, cat_id', 'safe', 'on'=>'search'),
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
			'cat' => array(self::BELONGS_TO, 'PostCat', 'cat_id'),
			'author' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
			'content' => 'Содержание',
			'tags' => 'Тэги',
			'seo_link' => 'Ссылка',
			'pub_date' => 'Дата',
			'status' => 'Статус',
			'cat_id' => 'Категория',
		);
	}

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->seo_link = UrlTranslit::translit($this->title);
            $i = 1;
            while (Post::model()->findByAttributes(array('seo_link'=>$this->seo_link), ($this->isNewRecord ? '' : "t.id != ".$this->id))!=null)
            {
                $this->seo_link = $this->seo_link.$i;
                $i++;
            }
            return true;
        } else
            return false;
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            $this->user_id = Yii::app()->user->id;
            $this->pub_date = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->pub_date);
            return true;
        } else
            return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->pub_date = Yii::app()->dateFormatter->format("dd.MM.yyyy", $this->pub_date);
        $this->_oldTags = $this->tags;
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('seo_link',$this->seo_link,true);
		$criteria->compare('pub_date',$this->pub_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('cat_id',$this->cat_id);

		return new CActiveDataProvider($this, array(
            'criteria'=>$this->ml->modifySearchCriteria($criteria),
		));
	}

    public function getShortContent()
    {
        $pos = strpos($this->content, '<div style="page-break-after: always;">');
        if ($pos == false)
            return $this->content;
        else
            return substr($this->content, 0, $pos);
    }

    public function getTagsItems() {
        $return = array();
        $tagsArray = explode(',', $this->tags);
        foreach ($tagsArray as $tag) {
            if (!empty($tag)) {
                $item = array();
                $item['label'] = CHtml::encode(trim($tag));
                $item['url'] = array('default/tag', 'name'=>CHtml::encode(trim($tag)));
                $return[] = $item;
            }
        }
        return $return;
    }

    public function scopes()
    {
        return array(
            'published'=>array(
                'condition'=>'status = '.self::STATUS_PUBLISHED.' AND pub_date <= NOW()'
            ),
        );
    }


    public function afterSave()
    {
        parent::afterSave();
        PostTag::updateFrequency($this->_oldTags, $this->tags);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        PostTag::updateFrequency($this->tags, '');
    }

    public function getStatusName()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return "Черновик";
                break;
            case self::STATUS_PUBLISHED:
                return "Опубликовано";
                break;
            default:
                return "Черновик";
        }
    }

    public function behaviors()
    {
        return array(
            'ml' => array(
                'class' => 'application.components.MultilingualBehavior',
                'localizedAttributes' => array(
                    'title', 'content'
                ),
                'langClassName' => 'PostLang',
                'langTableName' => 'post_lang',
                'langField' => 'lang_id',
                'localizedPrefix' => 'l_',
                'languages' => Language::getListLanguages(),
                'defaultLanguage' => Language::getDefaultLanguage()->name,
                'langForeignKey' => 'post_id',
                'dynamicLangClass' => true,
            ),
            'RouteBehavior'=>array(
                'class'=>'application.components.cms.CmsRouteBehavior',
                'route'=>'/blog/post/view',
                'module'=>'blog',
                'titleAttr'=>'title',
                'options'=>array(
                    'can_have_child'=>Route::NOT_CAN_HAVE_CHILD,
                    'is_clone'=>0,
                    'can_delete'=>1,
                    'create_child_item_link'=>array(
                        'route'=>'/blog/admin/post/create',
                        'param'=>'id',
                        'paramName'=>'category_id'
                    ),
                    'names'=>array(
                        '/blog/post/view'=>'Просмотр полной записи блога',
                    )
                ),
                'parent'=>array(
                    'model'=>'PostCat',
                    'attr'=>'cat_id'
                ),
            ),
        );
    }

    public function defaultScope()
    {
        return $this->ml->localizedCriteria();
    }
}