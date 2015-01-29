<?php
/* @var $this PageController */
/* @var $model Page */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'page-form',
    'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
	'enableAjaxValidation'=>false,
)); ?>

    <p class="note"><?php echo Yii::t('core/admin','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('core/admin','are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

    <?php $this->widget('application.components.MultiLanguageWidget', array(
        'model'=>$model,
        'viewItem'=>'_multiLanguageFields',
        'form'=>$form
    )); ?>



    <div class="row">
        <?php echo $form->labelEx($model,'author_id'); ?>
        <?php echo $form->dropDownList($model,'author_id', CHtml::listData(User::model()->findAll(), 'id', 'name')); ?>
        <?php echo $form->error($model,'author_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'pub_date'); ?>
        <?php $this->widget('application.extensions.timepicker.EJuiDateTimePicker', array(
            'model'=>$model,
            'attribute'=>'pub_date',
            'options'=>array(
                'timeFormat'=>'hh:mm:ss',
                'dateFormat'=>'yy-mm-dd'
            ),
        )); ?>
        <?php echo $form->error($model,'pub_date'); ?>
    </div>

    <div class="row buttons">
        <?php
        $label = $model->isNewRecord ? "Create" : "Save";
        $this->widget('bootstrap.widgets.TbButton',array(
            'label' => Yii::t('core/admin', $label),
            'buttonType' => 'submit',
            'type' => 'primary',
        )); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->