<?php

Yii::import('ext.nestedSortable.NestedSortableListView');

class StructureNestedSortableListView extends NestedSortableListView
{

    public function run()
    {
        $roots = Route::model()->roots()->findAll(array('order'=>'module ASC'));
        $models = array();
        foreach ($roots as $root) {
            if (isset($models[$root->module])) {
                $models[$root->module][] = $root;
            } else
                $models[$root->module] = array($root);
        }
        foreach ($models as $module=>$items) {
            $this->renderItems($module, $items, array('class'=>'sortable'));
        }

    }

    protected function renderItems($module, $items, $htmlOptions = array())
    {
        echo CHtml::openTag('div', array('class'=>'caption'));
            echo CHtml::tag('h1', array(), $module == null ? Yii::t('core/admin', 'Pages') : Yii::t('core/admin', 'Module').' '.$module);
            if ($module == null) {
                echo CHtml::openTag('div', array('class'=>'my-btn-holder'));
                    echo CHtml::openTag('a', array('class'=>'my-btn default','href'=> $this->controller->createUrl('/admin/page/create')));
                        echo CHtml::tag('span', array('class'=>'icons-modules'), '');
                        echo CHtml::tag('span', array('class'=>'text'), Yii::t('core/admin', 'Create page'));
                    echo Chtml::closeTag('a');
                echo Chtml::closeTag('div');
            }
        echo CHtml::closeTag('div');

        $this->renderSubItems($items, $htmlOptions);
    }

    protected function renderSubItems($models, $htmlOptions = array())
    {
        echo CHtml::openTag('ol', $htmlOptions);
        foreach ($models as $model) {
            echo CHtml::openTag('li', array('id'=>'list_'.$model->id, 'data-can_have_child'=>$model->admin_menu['can_have_child']));
            echo '<div><i class="icon-move"></i><span class="disclose"><i class="icon-chevron"></i></span>'.$model->{$this->titleAttribute}.
                CHtml::link('', $model->getAdminItemUrl('delete'), array('confirm'=>Yii::t('core/admin', 'Are you sure?'), 'class'=>'delete fui-cross')).
                CHtml::link('', $model->getAdminItemUrl('update'), array('class'=>'update fui-new')).
            CHtml::link('', $model->getViewItemUrl(), array('class'=>'view fui-eye', 'target'=>'_blank')).'</div>';
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
            ->registerPackage(self::PACKAGE_ID)
            ->registerScript(
                $this->id,
                "$('.sortable').nestedSortable({
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
                    stop: function (event, ui) {
                         var currentPosition = $(this).nestedSortable('getItem', { item: ui.item })
                         $.ajax({
                              type: 'POST',
                              url: '".$this->url."',
                              dataType: 'json',
                              data: { id: currentPosition.id, parent_id: currentPosition.parent_id, prev_id: currentPosition.prev_id },
                              success: function (response) {
                                    $(document).trigger({
                                          type:'move',
                                          response: response,
                                          item: $(ui.item)
                                    });
                              },
                              error: function (jqXHR, textStatus, errorThrown) {
                                    alert(jqXHR.responseText);
                              }
                        });
                    },
                    isAllowed: function (item, parent) {
                        var canHaveChild = $(parent).data('can_have_child');
                        return canHaveChild || canHaveChild === undefined;
                    },
                    isTree: true,
                    expandOnHover: 700
                });
                $('.disclose').on('click', function() {
                    $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
                });

                $(document).on('click', '.sortable a.delete', function (e) {
                    e.preventDefault();
                    var _this = $(this),
                        url = _this.attr('href');
                    $.post(url, {}, function () {
                        _this.closest('li').remove();
                    });
                });

                $(document).on('move', function (e) {
                    var response = e.response;
                    for (var i=0; i<response.viewItemUrl.length; i++) {
                        $('.sortable #list_'+response.viewItemUrl[i].id).find('>div a.view').attr('href', response.viewItemUrl[i].value);
                    }
                });

                ",
                CClientScript::POS_LOAD
            );
    }
} 