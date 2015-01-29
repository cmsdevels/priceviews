<?php
/* @var $this PostController */
/* @var $model Post */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#post-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('blog/admin', 'Blog'),
        Yii::t('blog/admin', 'Manage Post') =>array('/admin/blog/post/index'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('blog/admin', 'Manage Posts'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Create post'); ?></span>
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
	'id'=>'post-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'title',
            'editable' => array(
                'url' => $this->createUrl('updateField'),
                'inputclass' => 'span3'
            )
        ),
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'pub_date',
            'value' => '$data->pub_date',
            'editable' => array(
                'type' => 'date',
                'viewformat' => 'dd.mm.yyyy',
                'url' => $this->createUrl('updateField'),
            )
        ),
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'cat_id',
            'filter'=> false,
            'value' => '$data->cat->name',
            'sortable'=>false,
            'editable' => array(
                'type' => 'select',
                'url' => $this->createUrl('updateField'),
                'source'  => $this->createUrl('categoryList'),
            )
        ),
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
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}'
		),
	),
)); ?>
