<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
    <?php
       echo CHtml::encode($data->title);
    ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('version')); ?>:</b>
	<?php echo CHtml::encode($data->version); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>: </b>
         <?php echo CHtml::link(
            CHtml::encode($data->author_name),
            $data->author_url,
            array('target'=>'_blank')).', '.
            CHtml::encode($data->author_email); ?>
    <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo ($data->status == Widget::STATUS_DISABLE ? Yii::t('core/admin','Disable') : Yii::t('core/admin','Enable')); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('options')); ?>:</b>
    <?php echo CHtml::link(Yii::t('core/admin','Edit'), array('update','id'=>$data->id)); ?>
    <br />
    <?php echo CHtml::link(Yii::t('core/admin','Remove'), array('uninstall','id'=>$data->id)); ?>
    <br />

</div>