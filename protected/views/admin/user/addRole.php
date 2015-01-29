<?php
/* @var $this UserController */
/* @var $model AuthItemForm */
/* @var $form TbActiveForm */
?>

<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Users') => array('/admin/user/index'),
        Yii::t('core/admin', 'User roles') => array('/admin/user/roles'),
        Yii::t('core/admin', 'Add role'),
    );
?>
<?php $this->title = isset($model->name) ? Yii::t('core/user', 'Update role') : Yii::t('core/user', 'Add role'); ?>
<div class="caption">
    <h1><?php echo Yii::t('core/user', 'Add role'); ?></h1>
</div>
<div class="form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'addRole-form',
        'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note"><?php echo Yii::t('core/admin','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('core/admin','are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->toggleButtonRow($model, 'is_admin', array('onchange'=>'js: $(this).attr("checked") ? $("#admin_operations").show() : $("#admin_operations").hide()')); ?>
    </div>

    <div class="row well" id="admin_operations" style="<?php echo $model->is_admin ? '' : 'display: none;'; ?>">
        <?php echo $form->labelEx($model,'operations'); ?>
        <div class="listTree"></div>
        <button class="btn btn-success"><?php echo Yii::t('core/admin', 'Check All') ?></button>
        <button class="btn btn-danger"><?php echo Yii::t('core/admin', 'Un-Check All') ?></button>
        <button class="btn btn-info"><?php echo Yii::t('core/admin', 'Expand All') ?></button>
        <button class="btn btn-warning"><?php echo Yii::t('core/admin', 'Collapse All') ?></button>
        <?php $model->getItemsArray('operations'); ?>
    </div>


    <div class="row buttons">
        <?php
        $this->widget('bootstrap.widgets.TbButton',array(
            'label' => Yii::t('core/admin', 'Save'),
            'buttonType' => 'submit',
            'type' => 'primary',
        )); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->