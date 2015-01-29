<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 10.09.13
 * Time: 16:41
 */

class Deactivate extends CAction
{

    public function run()
    {
        $ids = Yii::app()->request->getParam('ids', array());
        $selectAll = Yii::app()->request->getParam('selected_all');
        $criteria = new CDbCriteria();
        if ($selectAll == 'false')
            $criteria->addInCondition('id', $ids);
        $result = CActiveRecord::model($this->modelName)->updateAll(
            array(
                $this->attribute=>$this->setValue
            ),
            $criteria
        );
    }


} 