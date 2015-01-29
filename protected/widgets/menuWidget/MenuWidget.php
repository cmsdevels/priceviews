<?php

class MenuWidget extends CmsWidget
{
    public function init()
    {
        $this->widget('zii.widgets.CMenu',array(
            'id'=>'main_menu',
            'activeCssClass'=>'active',
            'items'=>Menu::getItems($this->widget->options['menu']),
        ));
    }
}