<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 12.04.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
Yii::import('application.modules.comment.models.*');

class LastCommentsWidget extends CmsWidget {
    public function init()
    {
        $criteria = new CDbCriteria();
        $criteria->order = "pub_date DESC";
        $criteria->limit = (integer) $this->widget->options['countComments'];
        $dataProvider = new CActiveDataProvider('Comment', array('criteria'=>$criteria));
        $this->render("lastComments", array("dataProvider"=>$dataProvider));
    }
}