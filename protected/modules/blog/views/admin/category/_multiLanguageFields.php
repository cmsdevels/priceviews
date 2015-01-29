<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 02.06.14
 * Time: 14:58
 */
?>

<div class="row">
    <?php echo $form->labelEx($model,'name'); ?>
    <?php echo $form->textField($model,'name'.$suffix,array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'name'.$suffix); ?>
</div>