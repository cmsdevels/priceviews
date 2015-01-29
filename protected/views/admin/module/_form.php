<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'module-form',
    'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
	'enableAjaxValidation'=>false,
)); ?>

    <?php
    if ($form->errorSummary($model))
        Yii::app()->user->setFlash('error', $form->errorSummary($model));
    $this->widget('bootstrap.widgets.TbAlert', array(
        'closeText'=>false,
    ));
    ?>

    <?php if ($model->attributesNames) { ?>
    <div class="row">
        <?php echo $form->labelEx($model,'options'); ?>
        <?php $this->widget('application.components.cms.CmsSettingWidget', array(
        'model'=>$model,
        'attribute'=>'options',
        'attributesNames'=>$model->attributesNames,
    )); ?>
        <?php echo $form->error($model,'options'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('core/admin','Create') : Yii::t('core/admin','Save')); ?>
    </div>

    <?php } else { ?>

    <div class="row">
        <b><?php echo Yii::t('core/admin',"Module hasn't editable options."); ?></b>
    </div>

    <?php } ?>

<?php $this->endWidget(); ?>

</div><!-- form -->