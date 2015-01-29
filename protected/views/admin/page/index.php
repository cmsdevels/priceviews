<?php
/* @var $this PageController */
/* @var $model Page */

?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Pages'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Manage Pages'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text"><?php echo Yii::t('core/admin', 'Create page'); ?></span>
        </a>
    </div>
</div>

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'page-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'ajaxUrl'=>array('index'),
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'columns'=>array(
        'id',
        'title',
        array(
            'name'=>'content',
            'type'=>'raw',
            'value'=>'$data->shortContent'
        ),
        array(
            'name'=>'pub_date',
            'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'=>$model,
                    'attribute'=>'pub_date',
                    'language' => 'ru',
                    'htmlOptions' => array(
                        'class' => 'datepicker-filter',
                    ),
                    'options'=>array(
                        'dateFormat'=>'yy-mm-dd'
                    )
                ),
                true)
        ),

        array(
          'name'=>'author_id',
            'value'=>'$data->author->username'
        ),

        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),
    ),
)); ?>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('.datepicker-filter').datepicker(
            jQuery.extend(
                {showMonthAfterYear:false},
                jQuery.datepicker.regional['ru'],{'dateFormat':'yy-mm-dd'}
            )
        );
    }
    ");
?>
