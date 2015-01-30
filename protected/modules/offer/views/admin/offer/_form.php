<?php
/* @var $this OfferController */
/* @var $model Offer */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'offer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php echo $form->labelEx($model,'create_date'); ?>
        <?php $this->widget('application.extensions.timepicker.EJuiDateTimePicker', array(
            'model'=>$model,
            'attribute'=>'create_date',
            'options'=>array(
                'timeFormat'=>'hh:mm:ss',
                'dateFormat'=>'yy-mm-dd'
            ),
        )); ?>
        <?php echo $form->error($model,'create_date'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status', Offer::$listStatuses); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

    <?php echo $this->renderFile(Yii::getPathOfAlias('application.views.admin.metaData').'/_form.php', array('model'=>$model)); ?>


    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
            'model' => $model,
            'attribute' => 'description',
            'htmlOptions'=>array('value'=>$model->description)
        )); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
            'model' => $model,
            'attribute' => 'content',
            'htmlOptions'=>array('value'=>$model->content)
        )); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>


	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image'); ?>
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