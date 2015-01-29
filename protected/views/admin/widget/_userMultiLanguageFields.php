<div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title'.$suffix,array('size'=>60,'maxlength'=>125)); ?>
    <?php echo $form->error($model,'title'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textField($model,'description'.$suffix,array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'description'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'content'); ?>
    <?php echo $form->checkBox($model,'content_type', array('id' => 'Widget_content_type' . $suffix, 'style' => 'box-shadow: none; float: left')); ?>
    <?php echo $form->labelEx($model,'content_type', array('for' => 'Widget_content_type' . $suffix, 'style' => 'display: inline-block; margin-top: 11px; margin-left: 5px'));?>
    <?php $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
        'model' => $model,
        'attribute' => 'content'.$suffix,
    )); ?>
    <?php echo $form->error($model,'content'); ?>
</div>

<?php Yii::app()->clientScript->registerScript("contentTypeClick" . $suffix, "
        $(document).ready(function() {
            var suffix = '$suffix';
            var Widget_content = 'Widget_content' + suffix;
            var Widget_content_type = '#Widget_content_type' + suffix;
            $(Widget_content_type).on('change', function() {
                if ($(this).is(':checked'))
                    CKEDITOR.replace(Widget_content);
                else
                    CKEDITOR.instances[Widget_content].destroy();
            });
            CKEDITOR.on('instanceReady', function() {
                if (CKEDITOR.instances[Widget_content]) {
                    if (!$(Widget_content_type).is(':checked')) {
                        CKEDITOR.instances[Widget_content].destroy();
                    }
                }
            });
        });");?>