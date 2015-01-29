<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
	'enableAjaxValidation'=>false,
)); ?>

    <p class="note"><?php echo Yii::t('core/admin','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('core/admin','are required.'); ?></p>

    <?php
    if ($form->errorSummary($model))
        Yii::app()->user->setFlash('error', $form->errorSummary($model));
    $this->widget('bootstrap.widgets.TbAlert', array(
        'closeText'=>false,
    ));
    ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
        <?php echo $form->dropDownList($model,'role',CHtml::listData(Yii::app()->authManager->roles, 'name', 'description')); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status', array(User::STATUS_ACTIVE=>Yii::t('core/admin', 'Active'), User::STATUS_NOACTIVE=>Yii::t('core/admin','No active'))); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
    <?php
        $this->widget('bootstrap.widgets.TbButton',
            array(
                'label' => $model->isNewRecord ? Yii::t('core/admin', 'Create') : Yii::t('core/admin', 'Save'),
                'buttonType' => 'submit',
                'type' => 'primary',
            )
        );
    ?>

<?php $this->endWidget(); ?>

</div><!-- form -->