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