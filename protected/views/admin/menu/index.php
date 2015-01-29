<?php
/* @var $this MenuController */
/* @var $model Menu */
$this->breadcrumbs=array(
    Yii::t('core/admin', 'Menu'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#menu-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Manage Menu'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Create menu'); ?></span>
        </a>
    </div>
</div>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'menu-grid',
    'dataProvider'=>$model->adminSearchMenu(),
    'filter'=>$model,
    'ajaxUrl'=>array('index'),
    'columns'=>array(
        'title',
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),
    ),
)); ?>

