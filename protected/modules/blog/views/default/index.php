<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<div class="blog_text blog_main">
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_view',
        'summaryText'=>'',
        'pager'=>array(
            'htmlOptions'=>array('class'=>'pages_list'),
            'cssFile'=>false,
            'header'=>'<span class="title">Страница:</span>',
            'lastPageLabel'=>'Последняя',
            'nextPageLabel'=>'',
            'prevPageLabel'=>'',
            'firstPageLabel'=>'Первая',
            'selectedPageCssClass'=>'active',
        ),
    )); ?>
</div>
