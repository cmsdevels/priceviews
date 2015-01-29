<?php
/* @var $this CategoryController */
/* @var $model PostCat */

$this->breadcrumbs=array(
    Yii::t('blog/admin', 'Blog') =>array('/admin/blog/post/index'),
    Yii::t('blog/admin', 'Manage Categories'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#post-cat-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<div class="caption">
    <h1><?php echo Yii::t('blog/admin', 'Manage categories'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Create category'); ?></span>
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
    'id'=>'post-cat-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'ajaxUpdate'=>array('index'),
    'columns'=>array(
        'id',
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'name',
            'editable' => array(
                'url' => $this->createUrl('updateField'),
                'inputclass' => 'span3'
            )
        ),
        array(
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'status',
            'filter'=> false,
            'value' => '$data->statusName',
            'sortable'=>false,
            'editable' => array(
                'type'    => 'select',
                'url' => $this->createUrl('updateField'),
                'source'  => $this->createUrl('statusList'),
                'onInit' => 'js: function(e, editable) {
                    var colors = {0: "gray", 1: "green"};
                    $(this).css("color", colors[editable.value]);
                }',
                'options'  => array(    //custom display
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