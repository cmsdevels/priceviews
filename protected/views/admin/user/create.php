<?php
/* @var $this UserController */
/* @var $model User */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Users') => array('/admin/user/index'),
        Yii::t('core/admin', 'Add user'),
    );
?>

<?php $this->title = Yii::t('core/user', 'Create User'); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>