<?php
$this->breadcrumbs=array(
    Yii::t('core/admin', 'Modules') =>array('/admin/module/index'),
    Yii::t('core/admin', 'Settings modules') . ' - ' . $model->title,
);
?>

<?php $this->title = Yii::t('core/admin','Update Module').' '.  $model->title; ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>