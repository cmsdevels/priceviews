<?php
/* @var $this SettingsController */
/* @var $model Params */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Settings') =>array('/admin/settings/index'),
        Yii::t('core/admin', 'Add setting'),
    );
?>

<?php $this->title = Yii::t('core/admin', 'Create settings param'); ?>
<div class="caption">
    <h1><?php echo Yii::t('core/admin', 'Create settings param'); ?></h1>
</div>

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
        <?php echo $form->labelEx($model,'key'); ?>
        <?php echo $form->textField($model,'key',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'key'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'desc'); ?>
        <?php echo $form->textField($model,'desc',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'desc'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownList($model,'type',Params::typesList()); ?>
        <?php echo $form->error($model,'type'); ?>
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
