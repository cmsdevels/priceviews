<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
?>

<div class="caption">
    <h1><?php echo "<?php echo "; ?> Yii::t('<?php echo $this->getModule()->id; ?>/admin', 'Update <?php echo $this->pluralize($this->class2name($this->modelClass)); ?>'); ?></h1>
</div>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>