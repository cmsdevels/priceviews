<?php
/* @var $this NewsController */
/* @var $model News */
/* @var $data News */

?>

<?php
$this->title = Yii::t('news/admin', 'Manage News');

$gridId = 'news-grid';
$this->widget('bootstrap.widgets.TbExtendedGridView',
    array(
        'type'=>'striped bordered condensed',
        'id'=>'news-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'bulkActions' => array(
            'actionButtons' => array(
                array(
                    'buttonType' => 'button',
                    'id'=>'bulkActionsDraft',
                    'type' => 'danger',
                    'size' => 'small',
                    'label' => 'Сделать не активным',
                    'click' => 'js:function(values){updateSelected("#'.$gridId.'", values,"draft", "'.$this->createUrl('updateStatus').'","'.get_class($model).'");}'
                ),
                array(
                    'buttonType' => 'button',
                    'id'=>'bulkActionsActive',
                    'type' => 'primary',
                    'size' => 'small',
                    'label' => 'Опубликовать',
                    'click' => 'js:function(values){updateSelected("#'.$gridId.'", values,"published", "'.$this->createUrl('updateStatus').'","'.get_class($model).'");}'
                ),
            ),
            'checkBoxColumnConfig' => array(
                'name' => 'id'
            ),
        ),
        'columns'=>array(
            'id',
            'title',
            array(
                'class' => 'bootstrap.widgets.TbEditableColumn',
                'name' => 'date_create',
                'value' => '$data->date_create',
                'editable' => array(
                    'type' => 'date',
                    'title'=>'Укажите дату добавления',
                    'viewformat' => 'yyyy-mm-dd',
                    'url' => $this->createUrl('updateField'),
                )
            ),
            array(
                'name'=>'status',
                'value'=>'$data->getStatusText()'
            ),
            array(
                'name'=>'image',
                'type'=>'raw',
                'value'=>'CHtml::image(Yii::app()->request->baseUrl.News::IMAGE_PREVIEW_PATH.$data->image,"",array("style"=>"width:70px;"))'
            ),
            array(
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{update}{delete}'
            ),
        ),
    )
); ?>
<script type="text/javascript">
    function updateSelected(gridId, values, status, url, model) {
        var updateConfirmed = confirm("Изменить стаус?");
        if (updateConfirmed){
            var ids = [];
            values.each(function(){
                ids.push($(this).val());
            });
            $.ajax({
                url:url,
                type:'POST',
                data: {
                    items:ids,
                    status:status,
                    model:model
                },
                success: function(html){
                    $(gridId).yiiGridView("update");
                }
            });
        }
    }
</script>