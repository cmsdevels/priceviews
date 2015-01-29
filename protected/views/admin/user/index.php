<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
    Yii::t('core/admin', 'Users'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php $this->title = Yii::t('core/user', 'Manage Users'); ?>

<div class="caption">
    <h1><?php echo $this->title;?></h1>
</div>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'user-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'ajaxUrl'=>array('index'),
    'columns'=>array(
        'id',
        'username',
        'email',
        'name',
        array(
            'name'=>'role',
            'value'=>'Yii::app()->authManager->getAuthItem($data->role) !== null ? Yii::app()->authManager->getAuthItem($data->role)->description : $data->role',
            'filter'=>CHtml::activeDropDownList($model,'role',
                CHtml::listData(Yii::app()->authManager->roles, 'name', 'description'),
                array('empty'=>'------')
            ),
        ),
        array(
            'name'=>'status',
            'value'=>'$data->statusName',
            'filter'=>CHtml::activeDropDownList($model,'status', array(
                User::STATUS_ACTIVE => Yii::t('core/admin', 'Active'),
                User::STATUS_NOACTIVE => Yii::t('core/admin','No active'),

            ), array('empty'=>'------')),
        ),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}'
        ),
    ),
)); ?>
