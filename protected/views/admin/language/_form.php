<?php
/* @var $this LanguageController */
/* @var $model Language */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'language-form',
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
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>125)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList(
            $model,
            'status',
            array(
                Language::STATUS_NO_PUBLISHED=>Yii::t('core/admin','Disable'),
                Language::STATUS_PUBLISHED=>Yii::t('core/admin','Enable'),
            )
        );
        ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
    <?php
    $this->widget('bootstrap.widgets.TbButton',array(
        'label' => ($model->isNewRecord ? Yii::t('core/admin', 'Create') : Yii::t('core/admin', 'Save')),
        'buttonType' => 'submit',
        'type' => 'primary',
    ));
    ?>

<?php $this->endWidget(); ?>

</div><!-- form -->