<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 23.01.13
 * Time: 17:29
 * To change this template use File | Settings | File Templates.
 */

Yii::import('application.modules.blog.models.PostCat');

class WhereToGoWidget extends CWidget {

    public $title = null; //"<h4><span>Где отдохнуть</span></h4>";
    public $ulClass = null;

    public function init()
    {
        if ($this->title !== null && $this->ulClass !== null) {
            $postCats = PostCat::model()->published()->findAll();
            $this->render('whereToGoWidget',array(
                'title'=>$this->title,
                'postCats'=>$postCats,
                'ulClass'=>$this->ulClass));
        }
    }
}