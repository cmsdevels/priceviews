<?php
/* @var $this LanguageController */
/* @var $model Language */
?>

<?php $this->breadcrumbs=array(
    Yii::t('core/admin', 'Manage language') =>array('/admin/language/index'),
    Yii::t('core/admin', 'Edit language') . ' - ' . $model->title,
); ?>

<?php $this->title = Yii::t('core/admin','Update Language').' - '.$model->title; ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>