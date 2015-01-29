<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Widgets') => array('/admin/widget/index'),
        Yii::t('core/admin', 'Edit user widget') . ' - ' . $model->title,
    );
?>


<div class="caption">
    <h1><?php echo Yii::t('core/admin','Update user widget').' '.$model->name; ?></h1>
</div>

<?php echo $this->renderPartial('_userForm',array('model'=>$model,'listPosition'=>$listPosition)); ?>
