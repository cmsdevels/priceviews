<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$('#<?php echo $this->class2id($this->modelClass); ?>-grid').yiiGridView('update', {
data: $(this).serialize()
});
return false;
});
");
?>

<div class="caption">
    <h1><?php echo "<?php echo "; ?> Yii::t('<?php echo $this->getModule()->id; ?>/admin', 'Manage <?php echo $this->pluralize($this->class2name($this->modelClass)); ?>'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo "<?php echo " ?> $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text">
                <?php echo "<?php echo "; ?> Yii::t('<?php echo $this->getModule()->id; ?>/admin', 'Create <?php echo $this->pluralize($this->class2name($this->modelClass)); ?>'); ?>
            </span>
        </a>
    </div>
</div>

<?php echo "<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>"; ?>

<div class="search-form" style="display:none">
    <?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php"; ?> $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
'type'=>'striped bordered condensed',
'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'ajaxUrl'=>$this->createUrl('index'),
'columns'=>array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
    if(++$count==7)
        echo "\t\t/*\n";
    echo "\t\t'".$column->name."',\n";
}
if($count>=7)
    echo "\t\t*/\n";
?>
array(
'htmlOptions' => array('nowrap'=>'nowrap'),
'class'=>'bootstrap.widgets.TbButtonColumn',
'template'=>'{update}{delete}'
),
),
)); ?>
