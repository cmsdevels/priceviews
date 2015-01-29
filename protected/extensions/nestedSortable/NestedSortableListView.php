<?php
class NestedSortableListView extends CWidget {

    const PACKAGE_ID = 'jquery-nestedSortable';
    public $package = array();

    public $models;

    public $rootId = 'null';
    public $url;
    public $removeAction;
    public $updateAction;
    public $titleAttribute = 'title';

    public function init()
    {
        $this->package = array(
            'baseUrl' => $this->assetsUrl,
            'js' => array(
                'js/jquery.mjs.nestedSortable.js',
            )
        );

        $this->registerClientScript();
    }

    public function run()
    {
        if (!empty($this->models)&&is_array($this->models)) {
            $this->renderSubItems($this->models, array('class'=>'sortable'));
        }
    }

    protected function renderSubItems($models, $htmlOptions = array())
    {
        echo CHtml::openTag('ol', $htmlOptions);
        foreach ($models as $model) {
            echo CHtml::openTag('li', array('id'=>'list_'.$model->id));
            echo '<div><i class="icon-move"></i><span class="disclose"><i class="icon-chevron"></i></span>'.$model->{$this->titleAttribute}.
                CHtml::link('Remove', array($this->removeAction, 'id'=>$model->id), array('confirm'=>'Are you sure?')).
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
                          data: { id: currentPosition.id, parent_id: currentPosition.parent_id, prev_id: currentPosition.prev_id }
                        });
                    },

                    isTree: true,
                    expandOnHover: 700
                });
                $('.disclose').on('click', function() {
                    $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
                });",
                CClientScript::POS_LOAD
            );
    }

    public function getAssetsPath()
    {
        return __DIR__ . '/assets';
    }

    public function getAssetsUrl()
    {
        return Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
    }

}