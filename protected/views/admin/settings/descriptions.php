<?php
/* @var $this SettingsController */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Settings') =>array('/admin/settings/index'),
        Yii::t('core/admin', 'Setting description'),
    );
?>

<?php $this->title = Yii::t('core/admin', 'Manage Params Descriptions'); ?>
<div class="caption">
    <h1><?php echo Yii::t('core/admin', 'Manage Params Descriptions'); ?></h1>
</div>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'desc-grid',
    'dataProvider'=>$model->search(),
    'ajaxUrl'=>array('descriptions'),
    'filter'=>$model,
    'columns'=>array(
        'id',
        'key',
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'desc',
            'editable' => array(
                'url' => $this->createUrl('updateField'),
                'inputclass' => 'span3'
            )
        ),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{delete}'
        ),
    ),
)); ?>
