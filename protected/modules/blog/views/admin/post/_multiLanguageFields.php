<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 02.06.14
 * Time: 15:33
 */
?>

<div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title'.$suffix,array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'title'.$suffix); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'content'); ?>
    <?php $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
        'model' => $model,
        'attribute' => 'content'.$suffix
    )); ?>
    <?php echo $form->error($model,'content'.$suffix); ?>
</div>