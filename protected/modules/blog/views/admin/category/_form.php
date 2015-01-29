<?php
/* @var $this CategoryController */
/* @var $model PostCat */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-cat-form',
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
		<?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status', array(PostCat::STATUS_NO_PUBLISHED=>'Не опубликовано', PostCat::STATUS_PUBLISHED=>'Опубликовано')); ?>
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