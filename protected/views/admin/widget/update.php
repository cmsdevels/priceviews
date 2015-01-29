<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Widgets') => array('/admin/widget/index'),
        Yii::t('core/admin', 'Edit widget') . ' - ' . $model->title,
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Update Widget').' '.$model->title;; ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model,'listPosition'=>$listPosition)); ?>