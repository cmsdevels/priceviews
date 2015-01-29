<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Modules'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Modules'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('install'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Install module'); ?></span>
        </a>
    </div>
</div>

<?php
    $this->widget('zii.widgets.CListView', array(
        'summaryText'=>'',
        'dataProvider'=>$dataProvider,
        'itemView'=>'_view',
        'itemsCssClass'=>'block-modules row',
    )); ?>
