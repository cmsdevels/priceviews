<?php
/* @var $this AdminController */
/* @var $model Post */

?>

<?php
    $this->breadcrumbs=array(
        Yii::t('blog/admin', 'Blog'),
        Yii::t('blog/admin', 'Manage Post') =>array('/admin/blog/post/index'),
        Yii::t('blog/admin', 'New Post'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('blog/admin', 'Create post'); ?></h1>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>