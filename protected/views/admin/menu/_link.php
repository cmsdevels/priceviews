<div class="row">
    <?php echo CHtml::activeLabel($model,'url'); ?>
    <?php echo CHtml::activeTextField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo CHtml::error($model,'url'); ?>
</div>