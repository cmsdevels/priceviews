<ul class="<?php echo $options['ulClass']?>">
    <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_categoryNews',
            'summaryText'=>'',
        ));
    ?>
</ul>