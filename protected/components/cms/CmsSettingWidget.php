<?php

/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 14.02.13
 * Time: 11:06
 * To change this template use File | Settings | File Templates.
 */
class CmsSettingWidget extends CWidget
{
    public $model;
    public $attribute;
    public $attributesNames = true;

    protected $groups = array('not_multilingual' => '');
    protected $suffixList = array();

    public function init()
    {
        $this->suffixList = DMultilangHelper::suffixList(false);
        $this->fetchAttributes($this->attributesNames);
        $this->renderFormGroupElement();
    }

    public function fetchAttributes($attributes, $parent = '')
    {

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                if (!isset($value['name'])) {
                    $this->fetchAttributes($value, $key);
                } else {
                    $this->groupFormElement($key, $value, $parent);
                }
            }
        }
    }

    public function groupFormElement($key, $value, $parent)
    {
        foreach ($this->suffixList as $suffix => $name) {
            $row = '';
            if ($key === 'blockTitle') {
                $row .= CHtml::tag("h4", array(), $value['name']);
            } else {
                $langKey = $key . $suffix;

                $row .= CHtml::activeLabel($this->model, $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']', array('label' => $value['name'].'('.$name.')'));

                switch ($value['type']) {

                    case 'password':
                        $row .= CHtml::activePasswordField($this->model, $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']');
                        break;

                    case 'number':
                        $row .= CHtml::activeTextField($this->model, $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']');
                        break;

                    case 'text':
                        $row .= CHtml::activeTextArea($this->model, $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']');
                        break;

                    case 'list':
                        $row .= CHtml::activeDropDownList(
                            $this->model,
                            $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']',
                            is_array($value['values']) ? $value['values'] : array()
                        );
                        break;

                    case 'boolean':
                        $row .= CHtml::activeCheckBox($this->model, $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']');
                        break;
                    case 'editor':
                        $row .= $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
                            'model' => $this->model,
                            'attribute' => $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']',
                            'htmlOptions' => array()
                        ), true);
                        break;
                    case 'select':
                        if (isset($value['import']))
                            Yii::import($value['import']);
                        $row .= Chtml::activeDropDownList(
                            $this->model,
                            $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']',
                            CHtml::listData($value['model']::model()->findAllByAttributes(isset($value['criteria']) ? $value['criteria'] : array()), $value['valueAttribute'], $value['titleAttribute'])
                        );
                        break;

                    default:
                        $row .= CHtml::activeTextField($this->model, $this->attribute . ($parent ? '[' . $parent . ']' : '') . '[' . $langKey . ']');
                }
            }

            $row = CHtml::tag('div', array('class' => 'row'), $row);

            if (!isset($value['multilang']) || $value['multilang'] == false) {
                $this->groups['not_multilingual'] .= $row;
                break;
            } else {
                if (isset($this->groups[$name])) {
                    $this->groups[$name] .= $row;
                } else {
                    $this->groups[$name] = $row;
                }
            }

        }

    }

    public function renderFormGroupElement()
    {
        $tabs = array();
        foreach ($this->suffixList as $suffix => $name) {
            if (isset($this->groups[$name])) {
                $tabs[] = array(
                    'label' => $name,
                    'content' => $this->groups[$name],
                    'active' => (empty($tabs) ? true : false),
                    'paneOptions' => array(
                        'class' => 'white-block'
                    )
                );
            }
        }

        $this->widget('bootstrap.widgets.TbTabs', array(
            'type' => 'tabs',
            'tabs' => $tabs,
            'htmlOptions' => array(
                'class' => 'tab-container'
            )
        ));

        if (!empty($this->groups['not_multilingual'])) {
            echo $this->groups['not_multilingual'];
        }
    }
}
