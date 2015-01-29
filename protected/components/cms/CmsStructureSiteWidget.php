<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 17.07.13
 * Time: 14:19
 * To change this template use File | Settings | File Templates.
 */

Yii::import('application.extensions.nestedSortable.NestedSortableListView');

class CmsStructureSiteWidget extends NestedSortableListView
{
    public $createChildUrl;
    public $cloneAction;
    public $updateTreeAction;

    protected function renderSubItems($models, $htmlOptions = array())
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

    protected function registerClientScript()
    {
        Yii::app()->clientScript
            ->registerCoreScript('jquery')
            ->registerCoreScript('jquery.ui')
            ->addPackage(self::PACKAGE_ID, $this->package)
            ->registerPackage(self::PACKAGE_ID)->registerScript(
                $this->id,
                "
                function initialSortable() {
                    $('.sortable').nestedSortable({
                        forcePlaceholderSize: true,
                        handle: 'div i.icon-move',
                        helper:	'clone',
                        items: 'li',
                        opacity: .6,
                        placeholder: 'placeholder',
                        revert: 250,
                        tabSize: 25,
                        tolerance: 'pointer',
                        toleranceElement: '> div',
                        rootID: ".$this->rootId.",
                        isAllowed: function (placeholder, placeholderParent, originalItem) {
                            if ($(placeholderParent).hasClass('child_disable'))
                                return false;
                            else
                                return true;
                        },
                        stop: function (event, ui) {
                             var currentPosition = $(this).nestedSortable('getItem', { item: ui.item })
                             $.ajax({
                              type: 'POST',
                              url: '".$this->url."',
                              data: { id: currentPosition.id, parent_id: currentPosition.parent_id, prev_id: currentPosition.prev_id },
                              success: function () {
                                $.ajax({
                                  type: 'POST',
                                  url: '".$this->updateTreeAction."',
                                  data: {},
                                  success: function(data) {
                                    $('.sortable').nestedSortable('destroy');
                                    $('div.admin_content').html(data);
                                    initialSortable();
                                  }
                                });
                              }
                            });


                        },
                        isTree: true,
                        expandOnHover: 700
                    });
                };
                initialSortable();
                $('.disclose').on('click', function() {
                    $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
                });",
                CClientScript::POS_LOAD
            );
    }


}