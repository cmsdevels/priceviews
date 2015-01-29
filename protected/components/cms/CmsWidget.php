<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weblogic
 * Date: 2/12/13
 * Time: 3:42 PM
 * To change this template use File | Settings | File Templates.
 */

class CmsWidget extends CWidget
{
    public $widget;
    public $model = null;
    public function install()
    {

    }

    public function uninstall()
    {

    }

    public function render($view,$data=null,$return=false)
    {
        $data['widget'] = $this->widget;
        $this->controller->beginContent("//layouts/widgets/".$this->widget->layout, $data);
        parent::render($view,$data,$return);
        $this->controller->endContent();
    }

    public function renderData($content, $data, $execute=false)
    {
        $this->widget = $data['widget'];
        $this->controller->beginContent("//layouts/widgets/".$this->widget->layout, $data);
        if ($execute)
            eval("?> $content <?php ");
        else
            echo $content;
        $this->controller->endContent();
    }

    public function getMultilangOption($name)
    {
        $suffix = '';
        if (Language::getDefaultLanguage()->name != Yii::app()->language)
            $suffix = '_'.Yii::app()->language;
        if (isset($this->widget->options[$name.$suffix]))
            return $this->widget->options[$name.$suffix];
        else
            return null;
    }
}