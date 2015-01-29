<?php
/* @var $this SiteController */
/* @var $error array */
?>

<h2><?php echo Yii::t('core/site', 'Error').' - '.$code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>