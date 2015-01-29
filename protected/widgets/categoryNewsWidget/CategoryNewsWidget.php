<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 27.03.13
 * Time: 10:52
 * To change this template use File | Settings | File Templates.
 */

Yii::import('application.modules.news.models.*');

class CategoryNewsWidget extends CmsWidget
{
    public function init()
    {
        $systemLanguageId = Language::model()->findByAttributes(array('name'=>Yii::app()->language));
        $systemLanguageId = $systemLanguageId->id;
        $criteria = new CDbCriteria();
        $criteria->compare('lang_id',$systemLanguageId);
        $criteria->compare('status',1);
        $models = NewsCategory::model()->findAll($criteria);
        $lastNewsDataProvider = new CArrayDataProvider($models);
        $this->render('categoryNews',
            array(
                'dataProvider'=>$lastNewsDataProvider,
                'options'=>$this->widget->options
            )
        );
    }
}