<ul>
    <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_lastNews',
            'summaryText'=>'',
        ));
    ?>
</ul>