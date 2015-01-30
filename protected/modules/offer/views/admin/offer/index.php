<?php
/* @var $this OfferController */
/* @var $model Offer */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$('#offer-grid').yiiGridView('update', {
data: $(this).serialize()
});
return false;
});
");
?>

<div class="caption">
    <h1><?php echo  Yii::t('8061a75b/admin', 'Manage Offers'); ?></h1>
    <div class="my-btn-holder">
        <a class="my-btn default" href="<?php echo  $this->createUrl('create'); ?>">
            <span class="icons-modules"></span>
            <span class="text">
                <?php echo  Yii::t('8061a75b/admin', 'Create Offers'); ?>
            </span>
        </a>
    </div>
</div>


<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
'type'=>'striped bordered condensed',
'id'=>'offer-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'ajaxUrl'=>$this->createUrl('index'),
'columns'=>array(
		'id',
        'title',

        array(
            'name'=>'create_date',
            'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model'=>$model,
                    'attribute'=>'create_date',
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
            'class' => 'bootstrap.widgets.TbEditableColumn',
            'name' => 'status',
            'filter'=> false,
            'value' => '$data->statusName',
            'sortable'=>false,
            'editable' => array(
                'type' => 'select',
                'url' => $this->createUrl('updateField'),
                'source'  => $this->createUrl('statusList'),
                'onInit' => 'js: function(e, editable) {
                        var colors = {0: "gray", 1: "green"};
                        $(this).css("color", colors[editable.value]);
                    }',
                'options' => array(
                    'display' => 'js: function(value, sourceData) {
                              var selected = $.grep(sourceData, function(o){ return value == o.value; }),
                                  colors = {0: "gray", 1: "green"};
                              $(this).text(selected[0].text).css("color", colors[value]);
                          }'
                ),
            )
        ),

//		'description',
//		'content',
		/*
		'image',
		*/
array(
'htmlOptions' => array('nowrap'=>'nowrap'),
'class'=>'bootstrap.widgets.TbButtonColumn',
'template'=>'{update}{delete}'
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