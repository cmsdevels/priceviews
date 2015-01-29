<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 09.09.13
 * Time: 15:51
 */

class OrderingControllerBehavior extends CBehavior
{
    public $modelClass;

    public $groupAttribute = null;

    public function actionOrdering()
    {
        $id = Yii::app()->request->getParam('id');
        $action = Yii::app()->request->getParam('action');
        $attribute = Yii::app()->request->getParam('attribute');
        $criteria = new CDbCriteria();
        if ($id && $action && $attribute) {
            $movedModel = CActiveRecord::model($this->modelClass)->findByPk($id);
            if ($movedModel !== null) {
                if ($action == 'setValue') {
                    $value = Yii::app()->request->getParam('orderValue');
                    $movedModel->{$attribute} = $value;

                    $movedModel->save(false, array($attribute));
                } else {
                    if ($action == 'up') {
                        $criteria->addCondition($attribute.'<'.$movedModel->{$attribute});
                        $criteria->order = $attribute.' DESC';
                    } else {
                        $criteria->addCondition($attribute.'>'.$movedModel->{$attribute});
                        $criteria->order = $attribute.' ASC';
                    }
                    if ($this->groupAttribute !== null)
                        $criteria->compare($this->groupAttribute, $movedModel->{$this->groupAttribute});
                    $prevModel = CActiveRecord::model($this->modelClass)->find($criteria);
                    $movedOrdering = $movedModel->{$attribute};
                    $movedModel->{$attribute} = $prevModel->{$attribute};
                    $movedModel->save(false, array($attribute));
                    $prevModel->{$attribute} = $movedOrdering;
                    $prevModel->save(false, array($attribute));
                }
            }
        } else
            throw new CHttpException(404, 'Page doest not exist');
    }
} 