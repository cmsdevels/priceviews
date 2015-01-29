<div class="module col-md-4 col-sm-6">
    <a class="link-edit" href="<?php echo $this->createUrl('update', array('id'=>$data->id))?>"><span class="icons-pane"></span></a>
    <a class="link-delete" href="<?php echo $this->createUrl('uninstall', array('id'=>$data->id))?>"><span class="icons-delete"></span></a>
    <span class="icons-big module-image">
        <?php echo CHtml::image($data->getImageUrl()); ?>
    </span>
    <a class="name" href="<?php echo $this->createUrl('/'.strtolower($data->name).$data->admin_controller); ?>"><?php echo $data->title; ?></a>
    <div class="configuration">
        <div><?php echo Yii::t('core/admin', 'Description'); ?>: <strong><?php echo $data->description; ?></strong></div>
        <div><?php echo Yii::t('core/admin', 'Version'); ?>: <strong>1.0.0</strong></div>
        <div><?php echo Yii::t('core/admin', 'Author'); ?>: <a href="<?php echo $data->author_url; ?>" target="_blank"><?php echo $data->author_name?></a></div>
        <div><?php echo Yii::t('core/admin', 'Status'); ?>: <strong><?php echo ($data->status == Module::STATUS_DISABLE ? Yii::t('core/admin','Disabled') : Yii::t('core/admin','Enabled'));?></strong></div>
    </div>
</div>