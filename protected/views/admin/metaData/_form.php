<div class="row">
    <?php
        echo CHtml::activeLabelEx($model, 'meta_title');
        echo CHtml::activeTextField($model, 'meta[meta_title'.$suffix.']', array('value'=>$model->metaModel->{'meta_title'.$suffix}));
    ?>
</div>

<div class="row">
    <?php
    echo CHtml::activeLabelEx($model, 'meta_keywords');
    echo CHtml::activeTextArea($model, 'meta[meta_keywords'.$suffix.']', array('value'=>$model->metaModel->{'meta_keywords'.$suffix}));
    ?>
</div>

<div class="row">
    <?php
    echo CHtml::activeLabelEx($model, 'metaDescription');
    echo CHtml::activeTextArea($model, 'meta[meta_description'.$suffix.']', array('value'=>$model->metaModel->{'meta_description'.$suffix}));
    ?>
</div>