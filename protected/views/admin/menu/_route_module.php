<?php Yii::app()->clientscript->scriptMap['jquery.js'] = false; ?>
<div class="row module-route">
    <?php echo CHtml::dropDownList(
        'module',
        '',
        CHtml::listData(Module::model()->findAll(), 'name', 'title'),
        array(
            'id'=>'select_module',
            'empty'=>Yii::t('core/admin', 'Select module')
        )
    ); ?>
    <?php echo CHtml::activeDropDownList($model, 'url', Module::getRoutes(), array(
        'class'=>'moduleRoutes',
        'empty'=>Yii::t('core/admin', 'Select link')
    )); ?>
</div>