<?php
/* @var $this UserController */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Users') => array('/admin/user/index'),
        Yii::t('core/admin', 'User roles'),
    );
?>
<?php $this->title = Yii::t('core/user', 'Manage users roles'); ?>
<div class="caption">
    <h1><?php echo Yii::t('core/user', 'Manage users roles'); ?></h1>
</div>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'roles-grid',
    'dataProvider'=>$dataProvider,
    'ajaxUrl'=>array('roles'),
    'columns'=>array(
        array(
            'header'=>Yii::t('core/admin', 'Description'),
            'name'=>'description'
        ),
        array(
            'header'=>Yii::t('core/admin', 'Name'),
            'name'=>'name'
        ),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            'updateButtonUrl' => 'Yii::app()->controller->createUrl("updateRole", array("name"=>$data->name))',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("deleteRole", array("name"=>$data->name))',
        ),
    ),
)); ?>
