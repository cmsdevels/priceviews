<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'install-form',
    'enableAjaxValidation'=>false,
)); ?>

    <?php echo $form->errorSummary($model); ?>
    <h4>Данные сайта</h4>
    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'language'); ?>
        <?php echo $form->dropDownList($model,'language', $model::$languageCodes); ?>
        <?php echo $form->error($model,'language'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'db_host'); ?>
        <?php echo $form->textField($model,'db_host'); ?>
        <?php echo $form->error($model,'db_host'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'db_dbname'); ?>
        <?php echo $form->textField($model,'db_dbname'); ?>
        <?php echo $form->error($model,'db_dbname'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'db_username'); ?>
        <?php echo $form->textField($model,'db_username'); ?>
        <?php echo $form->error($model,'db_username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'db_password'); ?>
        <?php echo $form->textField($model,'db_password'); ?>
        <?php echo $form->error($model,'db_password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'db_tablePrefix'); ?>
        <?php echo $form->textField($model,'db_tablePrefix'); ?>
        <?php echo $form->error($model,'db_tablePrefix'); ?>
    </div>
    <h4>Данные администратора</h4>
    <div class="row">
        <?php echo $form->labelEx($model,'admin_email'); ?>
        <?php echo $form->textField($model,'admin_email'); ?>
        <?php echo $form->error($model,'admin_email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'admin_username'); ?>
        <?php echo $form->textField($model,'admin_username'); ?>
        <?php echo $form->error($model,'admin_username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'admin_pass'); ?>
        <?php echo $form->passwordField($model,'admin_pass'); ?>
        <?php echo $form->error($model,'admin_pass'); ?>
    </div>

    <button type="submit" class="btn btn-primary">
        <?php echo Yii::t('core/admin', 'Install');?>
    </button>

    <?php $this->endWidget(); ?>

</div><!-- form -->