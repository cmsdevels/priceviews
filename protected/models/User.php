<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $reg_date
 * @property string $role
 * @property integer $status
 * @property string $statusName
 */
class User extends CActiveRecord
{
    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $repeat_password;
    public $verifyCode;
    public $activation;
    public $oldPassword;
    public $role;
    public $oldRole;

    public $rules;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username,verifyCode', 'required', 'on'=>'registration'),
			array('role', 'required', 'on'=>'admin'),
            array('status', 'numerical', 'integerOnly'=>true),
            array('username, name, password, email', 'length', 'max'=>255),
            array('last_visit, block,type_id,rules,', 'safe'),
            // Registration
			array('repeat_password,rules', 'required', 'on'=>'activation'),
			array('repeat_password', 'required', 'on'=>'step_1'),
            array('rules','boolean', 'falseValue' => 'true','on'=>'activation'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password', 'on'=>'activation'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password', 'on'=>'step_1'),
			// Activation
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'registration'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username,rules, password, reg_date, type_id, status, role', 'safe', 'on'=>'search'),
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

    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            if ($this->id==1)
                return false;
            return true;
        } else
            return false;
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate())
        {
            return true;
        } else
            return false;
    }

    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord) {
                $this->salt = self::generateRandomString(10);
                $this->password = $this->hashPassword($this->password);
            } else {
                if ($this->password!=$this->oldPassword)
                    $this->password = $this->hashPassword($this->password);
            }

            return true;
        } else
            return false;
    }

    public function afterSave()
    {
        parent::afterSave();
        if ($this->id!=1) {
            $authManager = Yii::app()->authManager;
            $authManager->revoke($this->oldRole, $this->id);
            $authManager->assign($this->role, $this->id);
        }
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->oldPassword = $this->password;

        $roles = Yii::app()->authManager->getRoles($this->id);
        reset($roles);
        $this->oldRole = $this->role = key($roles);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => Yii::t('core/user','Username'),
			'password' => Yii::t('core/user','Password'),
			'repeat_password' => Yii::t('core/user','Confirm password'),
			'reg_date' => Yii::t('core/user','Registration date'),
			'status' => Yii::t('core/user','Status'),
			'statusName' => Yii::t('core/user','Status'),
			'role' => Yii::t('core/user','Role'),
			'email' => Yii::t('core/user','Email'),
			'name' => Yii::t('core/user','Name'),
            'verifyCode' => Yii::t('core/user','Captcha'),
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('role',$this->role,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function validatePassword($password)
    {
        return $this->hashPassword($password)===$this->password;
    }

    public function hashPassword($password)
    {
        return sha1($this->salt.$password);
    }

    public static function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function getStatusName()
    {
        switch($this->status) {
            case self::STATUS_ACTIVE:
                return Yii::t('core/admin', 'Active');
                break;
            case self::STATUS_NOACTIVE:
                return Yii::t('core/admin', 'No active');
                break;
            default:
                return "";
        }
    }

}