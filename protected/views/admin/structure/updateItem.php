<?php
    $this->title = 'Редактирование елемента структуры';
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'route-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'current_name'); ?>
        <?php echo $form->dropDownList($model,'current_name',$model->admin_menu['names']); ?>
        <?php echo $form->error($model,'current_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'url'); ?>
        <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'url'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->textField($model,'status'); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
    Yii::app()->clientScript->registerScript('translit', "
            $('#Route_url').syncTranslit({destination: 'Route_url'});
            $('#Route_title').syncTranslit({destination: 'Route_url'});
    ", CClientScript::POS_READY);
?>