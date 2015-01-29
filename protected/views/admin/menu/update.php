<?php
/* @var $this MenuController */
/* @var $model Menu */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Menu') =>array('/admin/menu/index'),
        Yii::t('core/admin', 'Edit menu') . ' - ' . $model->title ,
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Update Menu').' - '.$model->title; ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>