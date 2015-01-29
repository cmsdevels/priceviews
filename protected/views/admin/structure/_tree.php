<?php
    function renderSubItems($models, $htmlOptions = array())
    {
        echo CHtml::openTag('ol', $htmlOptions);
        foreach ($models as $model) {
            echo CHtml::openTag('li', array('id'=>'list_'.$model->id, 'class'=>($model->admin_menu['can_have_child'] == Route::NOT_CAN_HAVE_CHILD || $model->status == Route::STATUS_DISABLE_MOVE ? 'child_disable' : '')));
            echo '<div>'.($model->status == Route::STATUS_ENABLE_MOVE ? '<i class="icon-move"></i>' : '').'<span class="disclose"><i class="icon-chevron"></i></span>'.$model->title.' - <i>/'.$model->full_url.'</i>'.
                ($model->admin_menu['can_have_child'] ?
                    CHtml::link('Add child', array($model->admin_menu['create_child']['route'], $model->admin_menu['create_child']['param']=>$model->admin_menu['create_child']['value'])) :
                    ''
                ).
                ($model->admin_menu['can_delete'] ? CHtml::link('Remove', array($this->removeAction, 'id'=>$model->id), (!$model->admin_menu['is_clone'] ? array('confirm'=>'Are you sure?') : array())) : '').
                CHtml::link('Edit', array($this->updateAction, 'id'=>$model->id)).'</div>';
            $children = $model->children()->findAll();
            if (!empty($children))
                $this->renderSubItems($children);
            echo CHtml::closeTag('li');
        }
        echo CHtml::closeTag('ol');
    }

    renderSubItems($models, array());
?>