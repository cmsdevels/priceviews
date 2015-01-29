<?php
/**
 * Created by PhpStorm.
 * User: Sergiy
 * Company: http://web-logic.biz/
 * Email: sirogabox@gmail.com
 * Date: 02.06.14
 * Time: 11:53
 */

Yii::import('zii.widgets.grid.CCheckBoxColumn');
class CCheckBoxColumnCms extends CCheckBoxColumn{

    protected function renderHeaderCellContent()
    {
        /**
         * Renders the header cell content.
         * This method will render a checkbox in the header when {@link selectableRows} is greater than 1
         * or in case {@link selectableRows} is null when {@link CGridView::selectableRows} is greater than 1.
         */
        if(trim($this->headerTemplate)==='')
        {
            echo $this->grid->blankDisplay;
            return;
        }

        $item = '';
        if($this->selectableRows===null && $this->grid->selectableRows>1)
            $item = CHtml::checkBox($this->id.'_all',false,array('class'=>'select-on-check-all custom-check-box'));
        elseif($this->selectableRows>1)
            $item = CHtml::checkBox($this->id.'_all',false,array('class'=>'custom-check-box header'));
        else
        {
            ob_start();
            parent::renderHeaderCellContent();
            $item = ob_get_clean();
        }

        $completeItem =
            CHtml::openTag('div',array('class'=>'custom-check-box'))
                .$item.
                CHtml::label('',$this->id.'_all').
            CHtml::closeTag('div');

        echo strtr($this->headerTemplate,array(
//            '{item}'=>$completeItem,
            '{item}'=>$item.CHtml::label('',$this->id.'_all',array('class'=>'custom-check-box header')),
        ));
    }

    /**
     * Renders the data cell content.
     * This method renders a checkbox in the data cell.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row,$data)
    {
        if($this->value!==null)
            $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
        elseif($this->name!==null)
            $value=CHtml::value($data,$this->name);
        else
            $value=$this->grid->dataProvider->keys[$row];

        $checked = false;
        if($this->checked!==null)
            $checked=$this->evaluateExpression($this->checked,array('data'=>$data,'row'=>$row));

        $options=$this->checkBoxHtmlOptions;
        if($this->disabled!==null)
            $options['disabled']=$this->evaluateExpression($this->disabled,array('data'=>$data,'row'=>$row));

        $name=$options['name'];
        unset($options['name']);
        $options['value']=$value;
        $options['id']=$this->id.'_'.$row;
        $options['class']= ' custom-check-box';

//        echo CHtml::openTag('div',array('class'=>'custom-check-box'));
            echo CHtml::checkBox($name,$checked,$options);
            echo CHtml::label('',$options['id']);
//        echo CHtml::closeTag('div');
    }
} 