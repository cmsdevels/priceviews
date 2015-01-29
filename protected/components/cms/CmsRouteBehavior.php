<?php
/**
 * Created by JetBrains PhpStorm.
 * User: user
 * Date: 16.07.13
 * Time: 15:29
 * To change this template use File | Settings | File Templates.
 */

class CmsRouteBehavior extends CActiveRecordBehavior
{
    public $route = null;
    public $module = null;
    public $titleAttr = 'title';
    public $options = array();
    public $parent = null;

    public $status_model_attr = 'status';

    public $status = Route::STATUS_ENABLE_MOVE;
    public $seoAttr = null;
    public $pageTitleAttr = 'page_title';
    public $descAttr = 'description';

    public function afterSave($event)
    {
        /**
         * @var Route|NestedSetBehavior $model
         */
        $model = Route::model()->findByAttributes(array(
            'model'=>get_class($this->owner),
            'item_id'=>$this->owner->id
        ));
        if ($model == null) {
            $model = new Route();
            $model->name = $this->route;
            $model->current_name = $this->route;
            $model->url = UrlTranslit::translit($model->title);
            $model->full_url = $model->url;
            $model->model = get_class($this->owner);
            $model->item_id = $this->owner->id;
            $model->module = $this->module;
            if (isset($this->owner->lang_id))
                $model->lang_id = $this->owner->lang_id;
            else {
                $systemLang = Language::model()->findByAttributes(array('status'=>Language::STATUS_SYSTEM));
                $model->lang_id = $systemLang->id;
            }
            if (isset($this->options['create_child_item_link'])) {
                $this->options['create_child'] = array(
                    'route'=>$this->options['create_child_item_link']['route'],
                    'param'=>$this->options['create_child_item_link']['paramName'],
                    'value'=>$this->owner->{$this->options['create_child_item_link']['param']});
                unset($this->options['create_child_item_link']);
            }
            $model->admin_menu = $this->options;
            $model->status = $this->status;
        }
        $model->title = $this->owner->{$this->titleAttr};
        $model->url = UrlTranslit::translit($model->title);
        $model->full_url = $model->url;
        if (isset($model->admin_menu['additionalParent'])) {
            $temp = $model->admin_menu;
            $temp['additionalParent']['item_id'] = $this->owner->{$model->admin_menu['additionalParent']['model_attr']};
            $model->admin_menu = $temp;
        }
        $model->status_model = $this->owner->{$this->status_model_attr};
        if ($model->saveNode()) {
            if ($model->status == Route::STATUS_ENABLE_MOVE) {
                if ($this->parent !== null) {
                    $parentItem = Route::model()->findByAttributes(array(
                        'module'=>$model->module,
                        'model'=>$this->parent['model'],
                        'item_id'=>$this->owner->{$this->parent['attr']}
                    ));
                    if ($parentItem !== null && ($this->owner->{$this->parent['attr']} !== $this->owner->id || get_class($this->owner) != $this->parent['model'])){
                        $model->moveAsLast($parentItem);
                    }
                }
                $model->updateUrl();
            }
        }
    }

    public function afterDelete($event)
    {
        /**
         * @var Route|NestedSetBehavior $model
         */
        if ($this->owner->scenario != 'deleteRoute') {
            $model = Route::model()->findByAttributes(array(
                'model'=>get_class($this->owner),
                'item_id'=>$this->owner->id,
                'module'=>$this->module
            ));
            if ($model !== null) {
                $model->scenario = 'deleteBaseObject';
                $model->deleteNode(true);
            }
        }
    }

}