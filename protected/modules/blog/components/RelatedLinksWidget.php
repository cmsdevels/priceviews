<?php


class RelatedLinksWidget extends CWidget
{
    public $model=null;
    public function init()
    {
/**/


        $criteria = new CDbCriteria();

        $criteria->compare('cat_id',$this->model->cat_id);
        $criteria->compare('status',Post::STATUS_PUBLISHED);
        $criteria->limit = 5;
        $criteria->addSearchCondition('id',$this->model->id, false, 'NOT LIKE');



        $dataProvider = new CActiveDataProvider('Post',array(
            'criteria'=>$criteria,
            ));
        $this->render("index", array(

            'dataProvider'=>$dataProvider,
        ));
    }
}