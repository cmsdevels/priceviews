<?php
/* @var $this PageController */
/* @var $model Page */

?>

<?php
$this->breadcrumbs=array(
    Yii::t('core/admin', 'Pages') =>array('/admin/page/index'),
    Yii::t('core/admin', 'Create page'),
);
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Create page'); ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>