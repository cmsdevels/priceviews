<?php
/* @var $this LanguageController */
/* @var $model Language */

$this->breadcrumbs=array(
    Yii::t('core/admin', 'Manage languages'),
);
?>
<div class="caption">
    <h1><?php echo Yii::t('core/admin','Manage Languages'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Add language'); ?></span>
        </a>
    </div>
</div>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'language-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'ajaxUrl'=>array('index'),
    'columns'=>array(
        'name',
        'title',
        array(
            'name'=>'status',
            'value'=>'$data->statusText',
            'filter'=>array(
                Language::STATUS_NO_PUBLISHED=>Yii::t('core/admin','Disable'),
                Language::STATUS_PUBLISHED=>Yii::t('core/admin','Enable'),
                Language::STATUS_SYSTEM=>Yii::t('core/admin','System language'),
            )
        ),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            'buttons'=>array(
                'update'=>array(
                    'visible'=>'($data->status == Language::STATUS_SYSTEM ? false : true)',
                ),
                'delete'=>array(
                    'visible'=>'($data->status == Language::STATUS_SYSTEM ? false : true)',
                ),
            ),
        ),
    ),
)); ?>

