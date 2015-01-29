<?php
/* @var $this SiteController */
/* @var $error array */
?>
<div class="container">
    <h2><?php echo Yii::t('core/site', 'Error').' - '.$code; ?></h2>
    <br>
    <div class="error">
        <?php echo CHtml::encode($message); ?>
    </div>
</div>