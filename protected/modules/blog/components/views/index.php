<h2>Похожие статьи:</h2>
<?php $this->widget('zii.widgets.CListView', array(
    'id'=>'related-links',
    'dataProvider'=>$dataProvider,
    'itemView'=>'_related_links',
    'itemsTagName'=>'ul',
    'summaryText'=>'',
    'itemsCssClass'=>'similar_posts',
)); ?>
