<?php
/* @var $this CategoryController */
/* @var $model PostCat */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('blog/admin', 'Blog') => array('/admin/blog/post/index'),
        Yii::t('blog/admin', 'Category') => array('/admin/blog/category/index'),
        Yii::t('blog/admin', 'Update category - ') . $model->name,
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('blog/admin', 'Update category'); ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>