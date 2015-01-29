<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'module-form',
        'action'=>Yii::app()->createUrl(Yii::app()->request->getUrl()),
        'enableAjaxValidation'=>false,
    )); ?>

    <?php
    if ($form->errorSummary($model))
        Yii::app()->user->setFlash('error', $form->errorSummary($model));
    $this->widget('bootstrap.widgets.TbAlert', array(
        'closeText'=>false,
    ));
    ?>

    <?php $this->widget('application.components.MultiLanguageWidget', array(
        'model'=>$model,
        'viewItem'=>'_multiLanguageFields',
        'form'=>$form
    )); ?>

    <?php if ($model->attributesNames) { ?>
    <div class="row">
        <?php echo $form->labelEx($model,'options'); ?>
        <?php $this->widget('application.components.cms.CmsSettingWidget', array(
            'model'=>$model,
            'attribute'=>'options',
            'attributesNames'=>$model->attributesNames,
        )); ?>
        <?php echo $form->error($model,'options'); ?>
    </div>
    <hr/>
    <?php } ?>

    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList(
            $model,
            'status',
            array(
                Widget::STATUS_DISABLE=>Yii::t('core/admin','Disable'),
                Widget::STATUS_ENABLE=>Yii::t('core/admin','Enable'),
            )); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'is_cached'); ?>
        <?php echo $form->checkBox($model,'is_cached'); ?>
        <?php echo $form->error($model,'is_cached'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'cache_time'); ?>
        <?php echo $form->textField($model,'cache_time'); ?>
        <?php echo $form->error($model,'cache_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'order'); ?>
        <?php echo $form->textField($model,'order'); ?>
        <?php echo $form->error($model,'order'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'position'); ?>
        <?php echo $form->dropDownList($model,'position', $listPosition); ?>
        <?php echo $form->error($model,'position'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'layout'); ?>
        <?php echo $form->dropDownList($model,'layout', Widget::listLayouts()); ?>
        <?php echo $form->error($model,'layout'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'module'); ?>
        <?php echo $form->dropDownList(
            $model,
            'module',
            CHtml::listData(Module::model()->findAll(),'name','name'),
            array(
                'empty'=>Yii::t('core/admin', 'None'),
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>$this->createUrl('listControllers'),
                    'success'=>'function (data) {
                                    $("#'.CHtml::activeId($model,'controller').'").val("");
                                    $("#'.CHtml::activeId($model,'controller').'").html(data);
                                    $("#'.CHtml::activeId($model,'action').'").html("");
                                    $("#'.CHtml::activeId($model,'action').'").val("");

                               }'
                ),
            )
        ); ?>
        <?php echo $form->error($model,'module'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'controller'); ?>
        <?php echo $form->dropDownList(
            $model,
            'controller',
            ($model->module ? Module::listControllers($model->module) : Module::listControllers(null)),
            array(
                'empty'=>Yii::t('core/admin', 'None'),
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>$this->createUrl('listMethods'),
                    'update'=>'#'.CHtml::activeId($model,'action'),
                ),
            )
        ); ?>
        <?php echo $form->error($model,'controller'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'action'); ?>
        <?php echo $form->dropDownList(
            $model,
            'action',
            ($model->controller ? Module::listMethods($model->module, $model->controller) : array()),
            array('empty'=>Yii::t('core/admin', 'None'))
        ); ?>
        <?php echo $form->error($model,'action'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'item_id'); ?>
        <?php echo $form->textField($model,'item_id'); ?>
        <?php echo $form->error($model,'item_id'); ?>
    </div>
    <?php
    $this->widget('bootstrap.widgets.TbButton',array(
        'label' => Yii::t('core/admin','Save'),
        'buttonType' => 'submit',
        'type' => 'primary',
    )); ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->