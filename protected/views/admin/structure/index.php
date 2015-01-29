<?php
/**
 * @var Route[] $models
 */
?>

<?php
$this->breadcrumbs = array(
    Yii::t('core/admin', 'Structure'),
);
?>
<?php if ($models) { ?>
    <?php $this->widget('ext.nestedSortable.StructureNestedSortableListView', array(
        'models' => $models,
        'url' => $this->createUrl('move'),
    ));?>
<?php } else { ?>
    <div class="block-modules">
        <span class="empty"><?php echo Yii::t('core/admin', 'The structure is empty.') ?></span>
    </div>
<?php } ?>