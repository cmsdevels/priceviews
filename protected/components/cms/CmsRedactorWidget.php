<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 27.05.14
 * Time: 15:40
 */

class CmsRedactorWidget extends CWidget
{
    public $model;
    public $attribute;
    public $name;
    public $htmlOptions = array();
    public $options = array();

    public function init()
    {
        $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
            'model' => $this->model,
            'attribute' => $this->attribute,
            'htmlOptions'=>$this->options,
            'config'=>$this->options
        ));
    }
} 