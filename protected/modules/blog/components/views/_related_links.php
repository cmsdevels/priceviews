<?php
/* @var $data Post */
?>
<li>
    <?php echo CHtml::link(CHtml::encode($data->title), array('view', 'seo_link'=>$data->seo_link));?>
<?php /**/?>
</li>
