<?php
/* @var $this OfferItemsController */
/* @var $model OfferItems */
/* @var $form CActiveForm */
//print_r($model->getOfferList());
?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'offer-items-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'offer_id'); ?>
		<?php echo $form->dropDownList($model,'offer_id', Chtml::listData(Offer::model()->findAllByAttributes(
                array('status'=>1)), 'id', 'title'),
                array('empty'=>'Не выбрано')); ?>
		<?php echo $form->error($model,'offer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'link'); ?>
		<?php echo $form->textField($model,'link',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'offer_logo'); ?>
		<?php echo $form->textField($model,'offer_logo',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'offer_logo'); ?>
	</div>

    <div class="row buttons">
        <?php
        $this->widget('bootstrap.widgets.TbButton',array(
            'label' => ($model->isNewRecord ? Yii::t('core/admin', 'Create') : Yii::t('core/admin', 'Save')),
            'buttonType' => 'submit',
            'type' => 'primary',
        )); ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton',array(
            'label' => Yii::t('core/admin', 'Apply'),
            'buttonType' => 'submit',
            'type' => 'primary',
            'htmlOptions' => array(
                'name'=>'apply'
            ),
        )); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->