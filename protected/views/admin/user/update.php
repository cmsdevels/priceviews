<?php
/* @var $this UserController */
/* @var $model User */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Users') => array('/admin/user/index'),
        Yii::t('core/admin', 'Edit user') . ' - ' . $model->username,

    );
?>

<?php $this->title = Yii::t('core/user', 'Update User') . ' ' . $model->id; ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>