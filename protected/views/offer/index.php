<?php
/* @var $this OfferController */
/* @var $model Offer */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$('#offer-grid').yiiGridView('update', {
data: $(this).serialize()
});
return false;
});
");
?>

<div class="caption">
    <h1><?php echo  Yii::t('8061a75b/admin', 'Manage Offers'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo  $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text">
                <?php echo  Yii::t('8061a75b/admin', 'Create Offers'); ?>
            </span>
        </a>
    </div>
</div>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
'type'=>'striped bordered condensed',
'id'=>'offer-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'ajaxUrl'=>$this->createUrl('index'),
'columns'=>array(
		'id',
		'create_date',
		'status',
		'title',
		'description',
		'content',
		/*
		'image',
		*/
array(
'htmlOptions' => array('nowrap'=>'nowrap'),
'class'=>'bootstrap.widgets.TbButtonColumn',
'template'=>'{update}{delete}'
),
),
)); ?>
