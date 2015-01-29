<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 27.05.14
 * Time: 14:26
 */
?>

<div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title'.$suffix,array('size'=>60,'maxlength'=>125)); ?>
    <?php echo $form->error($model,'title'); ?>
</div>