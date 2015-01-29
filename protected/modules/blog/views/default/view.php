<?php
/* @var $this DefaultController */
/* @var $model Post */

$this->breadcrumbs = array(
    $this->module->id,
);
?>
<div class="blog_text">
    <h4><span><?php echo $model->title; ?></span></h4>

    <p class="published">Опубликовано: <?php echo $model->pub_date; ?></p>

    <?php echo $model->content; ?>
    <?php if (!empty($model->tags)) {
        $this->widget('zii.widgets.CMenu', array(
            'items' => $model->tagsItems,
            'htmlOptions'=>array(
                'class'=>'tags_list'
            )
        ));
    } ?>
</div>