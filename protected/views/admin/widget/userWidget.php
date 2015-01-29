<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Widgets') => array('/admin/widget/index'),
        Yii::t('core/admin', 'New user widget'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Create user widget'); ?></h1>
</div>

<?php echo $this->renderPartial('_userForm',array('model'=>$model,'listPosition'=>$listPosition)); ?>