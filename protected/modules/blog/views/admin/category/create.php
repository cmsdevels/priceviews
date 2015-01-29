<?php
/* @var $this CategoryController */
/* @var $model PostCat */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('blog/admin', 'Blog') => array('/admin/blog/post/index'),
        Yii::t('blog/admin', 'Categories') => array('/admin/blog/category/index'),
        Yii::t('blog/admin', 'New category'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('blog/admin', 'Create category'); ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>