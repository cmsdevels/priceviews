<?php
/* @var $this NewsController */
/* @var $model News */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'news-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    )
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
    <?php $this->widget('application.components.MultiLanguageWidget', array(
        'model'=>$model,
        'viewItem'=>'_multiLanguageFields',
        'form'=>$form
    )); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date_create'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model'=>$model,
            'attribute'=>'date_create',
            'options'=>array(
                'dateFormat'=>'dd-mm-yy',
            ),
        )); ?>
		<?php echo $form->error($model,'date_create'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',News::$listStatuses); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
        <?php if(!$model->isNewRecord){
            echo CHtml::image(Yii::app()->request->baseUrl.'/upload/news/preview/'.$model->image);
        }?>
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image',array('size'=>60,'maxlength'=>128)); ?>
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
