<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'menu-form',
        'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
        'enableAjaxValidation'=>false,
    )); ?>
        <p class="note"><?php echo Yii::t('core/admin','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('core/admin','are required.'); ?></p>

        <?php
        if ($form->errorSummary($model))
            Yii::app()->user->setFlash('error', $form->errorSummary($model));
            $this->widget('bootstrap.widgets.TbAlert', array(
                'closeText'=>false,
            ));
        ?>

        <?php $this->widget('application.components.MultiLanguageWidget', array(
            'model'=>$model,
            'viewItem'=>'_menuMultiLanguageFields',
            'form'=>$form
        )); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'status'); ?>
            <?php echo $form->dropDownList(
                $model,
                'status',
                array(
                    Menu::STATUS_NO_PUBLISHED=>Yii::t('core/admin','Disabled'),
                    Menu::STATUS_PUBLISHED=>Yii::t('core/admin','Enabled'),
                )
            );
            ?>
            <?php echo $form->error($model,'status'); ?>
        </div>

        <?php
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => ($model->isNewRecord ? Yii::t('core/admin', 'Create') : Yii::t('core/admin', 'Save')),
                'buttonType' => 'submit',
                'type' => 'primary',
            ));
        ?>

    <?php $this->endWidget(); ?>

    <?php if (!$model->isNewRecord) { ?>
        <div class="caption">
            <h1><?php echo Yii::t('core/admin','Manage menu items');?></h1>
            <a class="my-btn default" href="<?php echo $this->createUrl('/admin/menu/createItem'); ?>">
                <span class="icons-modules"></span>
                <span class="text"><?php echo Yii::t('core/admin', 'Add menu item'); ?></span>
            </a>
        </div>
        <?php
            $this->widget('ext.nestedSortable.NestedSortableListView', array(
                'models'=>$model->children()->findAll(),
                'rootId'=>$model->id,
                'url'=>$this->createUrl('moveItem'),
                'removeAction'=>'deleteItem',
                'updateAction'=>'updateItem'
            ));
        ?>
    <?php } ?>

</div><!-- form -->
