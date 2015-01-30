<?php
/* @var $this OfferItemsController */
/* @var $model OfferItems */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$('#offer-items-grid').yiiGridView('update', {
data: $(this).serialize()
});
return false;
});
");


?>

<div class="caption">
    <h1><?php echo  Yii::t('8061a75b/admin', 'Manage Offer Items'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo  $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text">
                <?php echo  Yii::t('8061a75b/admin', 'Create Offer Items'); ?>
            </span>
        </a>
    </div>
</div>

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
'type'=>'striped bordered condensed',
'id'=>'offer-items-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'ajaxUrl'=>$this->createUrl('index'),
'columns'=>array(
		'id',

    array(
        'type'=>'raw',
        'header'=>'Запись',
        'name'=>'offer_id',
        'value'=>'$data->actions',
        'filter'=>CHtml::dropDownList('OfferItems[offer_id]',$model->offer_id,Chtml::listData(Offer::model()->findAllByAttributes(
                array('status'=>1)), 'id', 'title'),
            array('empty'=>'Все')),

    ),

//		'offer_id',
		'create_date',
		'status',
		'title',
		'description',
		/*
		'price',
		'link',
		'image',
		'offer_logo',
		*/
array(
'htmlOptions' => array('nowrap'=>'nowrap'),
'class'=>'bootstrap.widgets.TbButtonColumn',
'template'=>'{update}{delete}'
),
),
)); ?>
