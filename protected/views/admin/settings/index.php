<?php
/**
 * @var ParamsForm $model
 * @var CActiveForm $form
 */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Settings'),
    );
?>

<?php $this->title = Yii::t('core/admin','General site settings');?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin', 'General site settings'); ?></h1>
</div>

<?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'fade' => true,
        'closeText' => '&times;', // false equals no close link
        'events' => array(),
        'htmlOptions' => array(),
        'alerts' => array(
            'success' => array('closeText' => '&times;'),
            'error' => array('closeText' => '&times;')
        ),
    ));
?>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'settings-form',
        'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>false,
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
            'class'=>'settings-form'
        )
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php foreach ($model->paramsDesc as $desc) { ?>

        <?php
        /**
         * @var Params $desc
         */
        ?>

        <div class="row">

            <?php echo CHtml::label(Yii::t('core/admin', $desc->desc), $desc->key); ?>
            <?php if ($desc->type == Params::TYPE_PLAIN_TEXT || $desc->type == Params::TYPE_EMAIL || $desc->type == Params::TYPE_URL) { ?>
                <?php echo $form->textField($model, $desc->key)?>
            <?php } else if ($desc->type == Params::TYPE_PASSWORD) { ?>
                <?php echo $form->passwordField($model, $desc->key)?>
            <?php } else if ($desc->type == Params::TYPE_IMAGE) { ?>
                <?php if ($desc->getFilePath($model->{$desc->key})) { ?>
                    <?php echo CHtml::image($desc->getFilePath($model->{$desc->key}));?>
                <?php } ?>
                <?php echo $form->fileField($model, $desc->key, array('class'=>'filestyle', 'data-iconName'=>"fui-search", 'data-buttonText'=>Yii::t("core/admin", "Select an image")))?>
            <?php } else if ($desc->type == Params::TYPE_TEXT_AREA) { ?>
                <?php echo $form->textArea($model, $desc->key)?>
            <?php } else if ($desc->type == Params::TYPE_TEXT_EDITOR) { ?>
                <?php $this->widget('application.extensions.yii-ckeditor.CKEditorWidget', array(
                    'model' => $model,
                    'attribute'=> $desc->key
                )); ?>
            <?php } else if ($desc->type == Params::TYPE_FILE) { ?>
                <?php if ($desc->getFilePath($model->{$desc->key})) { ;?>
                    <?php echo CHtml::tag('p', array(),$desc->getFilePath($model->{$desc->key}));?>
                <?php } ?>
                <?php echo $form->fileField($model, $desc->key,  array('class'=>'filestyle', 'data-iconName'=>"fui-search", 'data-buttonText'=>Yii::t("core/admin", "Select a file")))?>
            <?php } ?>

        </div>

    <?php } ?>

    <div>
        <?php
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => Yii::t('core/admin','Save'),
                'buttonType' => 'submit',
                'type' => 'primary',
                'htmlOptions'=>array(
                    'name'=>'save'
                )
            ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>

<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/admin/bootstrap-filestyle.min.js');
?>
