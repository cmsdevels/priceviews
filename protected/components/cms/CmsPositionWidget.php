<?php

Yii::import('application.models.Widget');

class CmsPositionWidget extends CmsWidget {

    public $position = null;
    public $model = null;

    public function init()
    {
        if ($this->position !== null) {
            $settingPosition = require(Yii::app()->theme->basePath.'/position.php');
            $maxItemCount = null;
            foreach ($settingPosition as $position) {
                if ($position['name']==$this->position && isset($position['maxItemCount']))
                    $maxItemCount = $position['maxItemCount'];
            }

            $criteria = new CDbCriteria();
            $criteria->compare('position', $this->position);
            $criteria->compare('status', Widget::STATUS_ENABLE);

            if (Yii::app()->controller->module)
                $criteria->addCondition('module IS NULL OR module="" OR module="'.Yii::app()->controller->module->id.'"');
            else
                $criteria->addCondition('module IS NULL OR module=""');

            $criteria->addCondition('controller IS NULL OR controller="" OR controller="'.Yii::app()->controller->id.'"');
            $criteria->addCondition('action IS NULL OR action="" OR action="'.Yii::app()->controller->action->id.'"');

            if ($maxItemCount !== null)
                $criteria->limit = $maxItemCount;
            $criteria->order = 't.order ASC';

            $widgets = Widget::model()->findAll($criteria);
            foreach ($widgets as $widget) {
                switch ($widget->type) {

                    case Widget::TYPE_SYSTEM:
                        $this->widget(
                            'application.widgets.'.strtolower(substr($widget->name,0,1)).substr($widget->name,1,strlen($widget->name)).'.'.$widget->name,
                            array(
                                'widget'=>$widget,
                                'model'=>$this->model,
                            )
                        );
                        break;

                    case Widget::TYPE_USER_HTML:
                        $this->renderData($widget->content, array('widget'=>$widget));
                        break;

                    case Widget::TYPE_USER_PHP:
                        $this->renderData($widget->content, array('widget'=>$widget), true);
                        break;
                }
            }

        }
    }
}