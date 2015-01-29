<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 12.09.13
 * Time: 9:34
 */

class InstallModuleForm extends CFormModel
{
    public $name;
    public $file;

    public function rules()
    {
        return array(
            array('name', 'required'),
            array('file', 'file', 'types'=>'zip', 'wrongType'=>'Не верный тип архива', 'allowEmpty'=>true),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'name' => Yii::t('core/admin', 'Name'),
            'file' => Yii::t('core/admin', 'Archive'),
        );
    }

    public function install()
    {
        $this->file = CUploadedFile::getInstance($this,'file');
        if ($this->file) {
            $fileName = uniqid().'.zip';
            $dir = Yii::getPathOfAlias('webroot.updates.archives').DIRECTORY_SEPARATOR;
            $this->file->saveAs($dir.$fileName, TRUE);
            return Module::install($this->name, $fileName);
        }
        return false;
    }
} 