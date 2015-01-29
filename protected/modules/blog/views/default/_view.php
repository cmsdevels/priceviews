<?php
/* @var $this DefaultController */
/* @var $data Post */
?>
<h4><span><?php echo CHtml::link(CHtml::encode($data->title), array('/blog/post/view', 'id'=>$data->id)); ?></span></h4>

<p class="published">Опубликовано: <?php echo $data->pub_date; ?></p>
<?php echo $data->shortContent; ?>

<?php if (!empty($data->tags)) {
    $this->widget('zii.widgets.CMenu', array(
        'items' => $data->tagsItems,
        'htmlOptions'=>array(
            'class'=>'tags_list'
        )
    ));
} ?>