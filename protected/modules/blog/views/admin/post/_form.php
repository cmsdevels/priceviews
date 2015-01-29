<?php
/* @var $this AdminController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Поля с <span class="required">*</span> обязательны к заполнению.</p>

	<?php
    if ($form->errorSummary($model))
        Yii::app()->user->setFlash('error', $form->errorSummary($model));
    $this->widget('bootstrap.widgets.TbAlert', array(
        'closeText'=>false,
    ));
    ?>

    <?php $this->widget('application.components.MultiLanguageWidget', array(
        'model'=>$model,
        'viewItem'=>'_multiLanguageFields',
        'form'=>$form
    )); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags'); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pub_date'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model'=>$model,
            'attribute'=>'pub_date',
            'options'=>array(
                'dateFormat'=>'dd-mm-yy'
            ),
        )); ?>
		<?php echo $form->error($model,'pub_date'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'cat_id'); ?>
        <?php
        echo $form->dropDownList($model,'cat_id', CHtml::listData(PostCat::model()->published()->findAll(), 'id', 'name'), array('empty'=>array(''=>'Не указано')));
        ?>
        <?php echo $form->error($model,'cat_id'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', array(Post::STATUS_DRAFT=>'Черновик', Post::STATUS_PUBLISHED=>'Опубликовано')); ?>
		<?php echo $form->error($model,'status'); ?>
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