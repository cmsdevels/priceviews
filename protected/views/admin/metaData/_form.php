<div class="row">
    <?php
        echo CHtml::activeLabelEx($model, 'meta_title');
        echo CHtml::activeTextField($model, 'meta[meta_title]', array('value'=>$model->metaModel->{'meta_title'}));
    ?>
</div>

<div class="row">
    <?php
    echo CHtml::activeLabelEx($model, 'meta_keywords');
    echo CHtml::activeTextArea($model, 'meta[meta_keywords]', array('value'=>$model->metaModel->{'meta_keywords'}));
    ?>
</div>

<div class="row">
    <?php
    echo CHtml::activeLabelEx($model, 'metaDescription');
    echo CHtml::activeTextArea($model, 'meta[meta_description]', array('value'=>$model->metaModel->{'meta_description'}));
    ?>
</div>