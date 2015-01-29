<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Modules') =>array('/admin/module/index'),
        Yii::t('core/admin', 'Install modules'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Install module'); ?></h1>
</div>


<?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'fade' => true,
        'closeText' => '&times;',
        'alerts' => array(
            'success' => array('closeText' => '&times;'),
            'error' => array('closeText' => '&times;')
        ),
    ));
?>


<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'primary',
    'toggle' => 'radio',
    'buttons' => array(
        array(
            'label'=>Yii::t('core/admin', 'Server'),
            'active'=>true,
            'htmlOptions'=>array(
                'onclick'=>'js: $("#install_achive").hide(); $("#install_server").show();'
            )
        ),
        array(
            'label'=>Yii::t('core/admin', 'Archive'),
            'htmlOptions'=>array(
                'onclick'=>'js: $("#install_server").hide(); $("#install_achive").show();'
            )
        ),
    ),
)); ?>


<div class="form" id="install_achive" style="display: none;">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'install-module-form',
        'action'=>array('/admin/module/installArchive'),
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>

    <div class="row">
        <?php echo $form->labelEx($installModel,'name'); ?>
        <?php echo $form->textField($installModel,'name'); ?>
        <?php echo $form->error($installModel,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($installModel,'file'); ?>
        <?php echo $form->fileField($installModel,'file', array('class'=>'filestyle', 'data-iconName'=>"fui-search", 'data-buttonText'=>Yii::t("core/admin", "Select a file"))); ?>
        <?php echo $form->error($installModel,'file'); ?>
    </div>

    <?php
    $this->widget('bootstrap.widgets.TbButton',array(
        'label' => Yii::t('core/admin', 'Install'),
        'buttonType' => 'submit',
        'type' => 'primary',
    )); ?>

    <?php $this->endWidget(); ?>
</div>

<div id="install_server">
<?php
if($dataProvided){
    $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
        'type'=>'striped bordered condensed',
        'id'=>'install-modules-grid',
        'ajaxUrl'=>array('install'),
        'dataProvider'=>$dataProvided,
        'columns'=>array(
            array(
                'name' => Yii::t('core/admin','ID'),
                'type' => 'raw',
                'value' => 'CHtml::encode($data["id"])'

            ),
            array(
                'name' => Yii::t('core/admin','Title'),
                'type' => 'raw',
                'value' => 'CHtml::encode($data["title"])'

            ),
            array(
                'name' => Yii::t('core/admin','Description'),
                'type' => 'raw',
                'value' => 'CHtml::encode($data["desc"])'

            ),
            array(
                'name' => Yii::t('core/admin','Version'),
                'type' => 'raw',
                'value' => 'CHtml::encode($data["version"])'

            ),
            array(
                'name' => Yii::t('core/admin', 'Author name'),
                'type' => 'raw',
                'value' => 'CHtml::link(CHtml::encode($data["author"]), $data["web_site"], array("target"=>"_blank"))'

            ),
            array(
                'htmlOptions' => array('nowrap'=>'nowrap'),
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'buttons'=>array(
                    'install'=>array(
                        'label'=>Yii::t('core/admin','Install'),
                        'visible'=>'isset($data["need_upgrade"]) ? false : true',
                        'url'=> 'Yii::app()->createUrl("/admin/module/install", array("module"=>$data["name"],"action"=>"install", "id"=>$data["id"]))',
                    ),
                    'upgrade'=>array(
                        'label'=>Yii::t('core/admin','Upgrade'),
                        'visible'=>'isset($data["need_upgrade"]) ? true : false',
                        'url'=> 'Yii::app()->createUrl("/admin/module/install", array("module"=>$data["name"],"action"=>"upgrade", "id"=>$data["id"]))',
                    ),
                ),
                'template'=>'{install}{upgrade}',
            ),
        ),
    ));
}else{
    if(Yii::app()->user->hasFlash('errorServer')){
        echo CHtml::tag('p',array(),Yii::app()->user->getFlash('errorServer'),true);
    }
} ?>
</div>

<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/admin/bootstrap-filestyle.min.js');
?>