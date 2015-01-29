<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 05.11.13
 * Time: 11:32
 */

class MultiLanguageWidget extends CWidget
{
    public $model;
    public $viewItem;
    public $form;

    public function init()
    {
        $tabs = array();
        $languages = Language::getListLanguages();
        $defaultLanguage = Language::getDefaultLanguage()->name;
        foreach ($languages as $name=>$label) {
            $suffix = ($name == $defaultLanguage ? '' : '_'.$name);
            $row = $this->owner->renderPartial($this->viewItem, array('model'=>$this->model, 'form'=>$this->form, 'suffix'=>$suffix), true);
            $tabs[] = array(
                'label'=>$label,
                'content'=>$row,
                'active'=>(empty($tabs)? true:false),
                'paneOptions'=>array(
                    'class'=>'white-block'
                )
            );
        }
        $this->widget('bootstrap.widgets.TbTabs', array(
            'type'=>'tabs',
            'tabs'=>$tabs,
            'htmlOptions'=>array(
                'class'=>'tab-container'
            )
        ));
    }
} 