<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 14.02.13
 * Time: 14:20
 * To change this template use File | Settings | File Templates.
 */
Yii::import('application.modules.news.models.*');

class LastNewsWidget extends CmsWidget
{
    public function init()
    {
        $systemLanguageId = Language::model()->findByAttributes(array('name'=>Yii::app()->language));
        $systemLanguageId = $systemLanguageId->id;

        $criteria = new CDbCriteria();
        $criteria->compare('lang_id',$systemLanguageId);
        $criteria->compare('status',1);
        $criteria->order = 'pub_date DESC';
        $criteria->limit = $this->widget->options['countNews'];
        $models = News::model()->findAll($criteria);
        $lastNewsDataProvider = new CArrayDataProvider($models);
        $this->render('lastNewsWidget', array(
            'dataProvider'=>$lastNewsDataProvider));

    }
}
