<div class="sb_block">
    <ul>
        <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_lastComments',
            'summaryText'=>'',
        ));
        ?>
    </ul>
</div>