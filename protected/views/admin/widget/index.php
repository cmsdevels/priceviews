<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Widgets'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Widgets'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('install'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Install Widget'); ?></span>
        </a>
        <a class="my-btn default" href="<?php echo $this->createUrl('addUserWidget'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Create user widget'); ?></span>
        </a>
    </div>
</div>

<?php if(Yii::app()->user->hasFlash('success')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('success'); ?>
</div>

<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('error')): ?>

<div class="flash-error">
    <?php echo Yii::app()->user->getFlash('error'); ?>
</div>

<?php endif; ?>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'widget-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'ajaxUrl' => array('index'),
    'columns'=>array(
        'id',
        'name',
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'title',
            'editable' => array(
                'url' => $this->createUrl('updateField'),
                'inputclass' => 'span3'
            )
        ),
        'author_name',
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'status',
            'filter'=> false,
            'value' => '$data->statusName',
            'sortable'=>false,
            'editable' => array(
                'type' => 'select',
                'url' => $this->createUrl('updateField'),
                'source'  => $this->createUrl('statusList'),
                'onInit' => 'js: function(e, editable) {
                    var colors = {0: "gray", 1: "green"};
                    $(this).css("color", colors[editable.value]);
                }',
                'options' => array(
                    'display' => 'js: function(value, sourceData) {
                          var selected = $.grep(sourceData, function(o){ return value == o.value; }),
                              colors = {0: "gray", 1: "green"};
                          $(this).text(selected[0].text).css("color", colors[value]);
                      }'
                ),
            )
        ),
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'position',
            'filter'=> false,
            'value' => '$data->position',
            'sortable'=>false,
            'editable' => array(
                'type' => 'select',
                'url' => $this->createUrl('updateField'),
                'source'  => $this->createUrl('positionList'),
            )
        ),
//        'position',
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}{copy}',
            'buttons'=>array(
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("/admin/widget/uninstall", array("id"=>$data->id))',
                ),
                'copy'=>array(
                    'label'=>Yii::t('core/admin', 'Copy'),
                    'url'=>'Yii::app()->createUrl("/admin/widget/copy", array("id"=>$data->id))',
                    'visible'=>'$data->parent_id == null ? true : false',
                ),
            ),
        ),
    ),
)); ?>