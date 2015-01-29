<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 18.07.13
 * Time: 9:20
 * To change this template use File | Settings | File Templates.
 */

class CmsActiveRecord extends CActiveRecord
{
    public function getChildren()
    {
        $routes = Route::getItem(get_class($this), $this->id, Yii::app()->getController()->module->id)->children()->findAll();
        $items = array();
        foreach($routes as $route) {
            $items[] = CActiveRecord::model($route->model)->findByPk($route->item_id);
        }
        return $items;
    }

    public function getDescendants()
    {
        $routes = Route::getItem(get_class($this), $this->id, Yii::app()->getController()->module->id)->descendants()->findAll();
        $items = array();
        foreach($routes as $route) {
            $items[] = CActiveRecord::model($route->model)->findByPk($route->item_id);
        }
        return $items;
    }

    public function getDescendantsByModel($model)
    {
        $routes = Route::getItem(get_class($this), $this->id, Yii::app()->getController()->module->id)->descendants()->findAll('model = :model', array(':model'=>$model));
        $itemIds = array();
        foreach ($routes as $route)
            $itemIds[] = $route->item_id;
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $itemIds);
        return CActiveRecord::model($model)->findAll($criteria);
    }

    public function getChildrenByModel($model)
    {
        $routes = Route::getItem(get_class($this), $this->id, Yii::app()->getController()->module->id)->children()->findAll('model = :model', array(':model'=>$model));
        $items = array();
        foreach($routes as $route) {
            $items[] = CActiveRecord::model($route->model)->findByPk($route->item_id);
        }
        return $items;
    }

}