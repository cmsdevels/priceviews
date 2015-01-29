<div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title'.$suffix,array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'title'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model,'content'); ?>
    <?php $this->widget('application.components.cms.CmsRedactorWidget', array(
        'model' => $model,
        'attribute' => 'content'.$suffix,
    )); ?>
    <?php echo $form->error($model,'content'); ?>
</div>
<?php echo $this->renderFile(Yii::getPathOfAlias('application.views.admin.metaData').'/_form.php', array('model' => $model, 'suffix' => $suffix)); ?>