<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 16.05.13
 * Time: 16:30
 * To change this template use File | Settings | File Templates.
 */

class MetaDataBehavior extends CActiveRecordBehavior
{

    protected  $_metaModel = null;

    public function getMeta()
    {
        if ($this->_metaModel !== null)
            return $this->_metaModel->attributes;
    }

    public function setMeta($data)
    {
        if ($this->_metaModel === null)
            $this->_metaModel = new MetaData();
        $this->_metaModel->attributes = $data;
    }

    public function afterFind($event)
    {
        $this->_metaModel = MetaData::model()->multilang()->findByAttributes(array(
            'model'=>get_class($this->owner),
            'model_id'=>$this->owner->id
        ));
        if ($this->_metaModel === null) {
            $this->_metaModel = new MetaData();
            $this->_metaModel->model = get_class($this->owner);
            $this->_metaModel->model_id = $this->owner->id;
        }
    }

    public function afterSave($event)
    {
        $this->_metaModel->model = get_class($this->owner);
        $this->_metaModel->model_id = $this->owner->id;
        $this->_metaModel->save(false);
    }

    public function attach($owner) {
        parent::attach($owner);
        $validators = $this->owner->getValidatorList();
        $validator = CValidator::createValidator('safe', $this->owner, 'meta');
        $validators->add($validator);

    }

    public function getMetaModel()
    {
        if ($this->_metaModel == null) {
            $this->_metaModel = new MetaData();
            $this->_metaModel->model = get_class($this->owner);
        }
        return $this->_metaModel;
    }
}