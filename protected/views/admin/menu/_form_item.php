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
        'viewItem'=>'_multiLanguageFields',
        'form'=>$form
    )); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'parent_id'); ?>
        <?php if($model->isMenuItems()){
            echo $form->dropDownList($model, 'parent_id', $model->getMenuItemsListData());
        }else{
            echo $form->hiddenField($model,'parent_id');
            echo CHtml::tag('p',array('style'=>'color:red'),Yii::t('core/admin','Create at least one main menu'),true);
        }?>
        <?php echo $form->error($model,'parent_id'); ?>
    </div>

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

    <div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownList(
            $model,
            'type',
            array(
                Menu::TYPE_ROUTE=>Yii::t('core/admin','Route'),
                Menu::TYPE_LINK=>Yii::t('core/admin','Link'),
                Menu::TYPE_ROUTE_MODULE=>Yii::t('core/admin','Route module'),
            ),
            array(
                'class'=>'select_menu_type_dropdown'
            )
        );
        ?>
        <?php echo $form->error($model,'type'); ?>
    </div>

    <div id="variable_type">


    </div>

    <div class="row">
        <?php
            $this->widget('bootstrap.widgets.TbButton',array(
                'label' => ($model->isNewRecord ? Yii::t('core/admin', 'Create') : Yii::t('core/admin', 'Save')),
                'buttonType' => 'submit',
                'type' => 'primary',
            ));
        ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php Yii::app()->getClientScript()->registerScript('select_menu_type', "
    var menu_item_id = ".($model->isNewRecord ? CJSON::encode(null) : $model->id).";
    var menu_item_type = ".($model->type).";
    var typeRoute = ".Menu::TYPE_ROUTE.";
    var typeLink = ".Menu::TYPE_LINK.";
    var routeIdFieldId = '".CHtml::activeId($model, 'route_id')."';
    var variableBlock = $('#variable_type');

    function reinstallJs(type) {
        if (type == typeRoute) {
            var selected = $('#variable_type div').find('a.selected').parent().attr('id');
            $('#variable_type div').jstree({
                core : {
			        'open_parents':true
		        },
		        'ui' : {
                    'select_limit' : 1,
                    'initially_select': [selected]
                },
		        'plugins':['ui', 'themes','html_data'],
		        themes:{'theme':'structure','dots':true,'icons':true},
		    }).on('loaded.jstree', function() {
                $('#variable_type div').jstree('open_all');
            }).bind('select_node.jstree', function (NODE, REF_NODE) {
                var a = $.jstree._focused().get_selected();
                var route_id = $(a).attr('id').replace('node_', '');
                $('#'+routeIdFieldId).val(route_id);
            });
        }
    };

    function loadVariableBlock(typeItem) {
        $.post(
                '/admin/menu/getTypeMenuForm',
                {type: typeItem, menu_item_id: menu_item_id},
                function (html) {
                    variableBlock.html(html);
                    reinstallJs(typeItem);
                }
            );
    };

    $(document).on('change', '.select_menu_type_dropdown', function () {
        var selectType = $(this).val();
        if (selectType != '') {
            loadVariableBlock(selectType);
        } else {
            variableBlock.html('');
        }
    });

    (function () {
        loadVariableBlock(menu_item_type);
    })();

    $(document).on('change', '#select_module', function () {
        var module = $(this).val();
        $.post(
            '/admin/menu/moduleRoutes',
            {module: module},
            function (data) {
                $('.moduleRoutes').html(data);
            }
        );
    });


", CClientScript::POS_READY); ?>
<?php
    Yii::app()->bootstrap->registerAssetCss('select2.css');
    Yii::app()->bootstrap->registerAssetJs('select2.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.extensions.structure-jstree.js_plugins')) . '/jstree/jquery.jstree.js', CClientScript::POS_END);
?>