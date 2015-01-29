<?php
    $this->breadcrumbs=array(
        Yii::t('core/admin', 'Widgets') => array('/admin/widget/index'),
        Yii::t('core/admin', 'Install Widgets'),
    );
?>

<div class="caption">
    <h1><?php echo Yii::t('core/admin','Install Widget'); ?></h1>
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

<?php $this->widget('application.components.cms.cmsGridView.CmsGridView', array(
    'type'=>'striped bordered condensed',
    'id'=>'widget-install-grid',
    'dataProvider'=>$dataProvided,
    'ajaxUrl' => array('install'),
    'columns'=>array(
        array(
            'name' => Yii::t('core/admin','Title'),
            'type' => 'raw',
            'value' => 'CHtml::encode($data["title"])'

        ),
        array(
            'name' => Yii::t('core/admin','Description'),
            'type' => 'raw',
            'value' => 'CHtml::encode($data["description"])'

        ),
        array(
            'name' => Yii::t('core/admin','Version'),
            'type' => 'raw',
            'value' => 'CHtml::encode($data["version"])'

        ),
        array(
            'name' => Yii::t('core/admin', 'Author name'),
            'type' => 'raw',
            'value' => 'CHtml::encode($data["author"]["name"])'

        ),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'install'=>array(
                    'label'=>Yii::t('core/admin','Install'),
                    'visible'=>'isset($data["need_upgrade"]) ? false : true',
                    'url'=> 'Yii::app()->createUrl("/admin/widget/install", array("widget"=>$data["name"],"action"=>"install"))',
                ),
                'upgrade'=>array(
                    'label'=>Yii::t('core/admin','Upgrade'),
                    'visible'=>'isset($data["need_upgrade"]) ? true : false',
                    'url'=> 'Yii::app()->createUrl("/admin/widget/install", array("widget"=>$data["name"],"action"=>"upgrade"))',
                ),
            ),
            'template'=>'{install}{upgrade}',
        ),
    ),
)); ?>