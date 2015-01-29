<?php
/* @var $this LanguageController */
/* @var $model Language */
?>

<?php $this->breadcrumbs=array(
    Yii::t('core/admin', 'Manage language') => array('/admin/language/index'),
    Yii::t('core/admin', 'Add language'),
); ?>
<div class="caption">
    <h1><?php echo Yii::t('core/admin','Add language'); ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>