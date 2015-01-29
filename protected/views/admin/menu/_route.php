<?php echo CHtml::activeHiddenField($model,'route_id'); ?>
<div class="row">
    <?php
        $categories = Route::model()->findAll(array('order' => 'root, lft'));
        $level = 0;
        foreach ($categories as $n => $category) {

            if ($category->level == $level)
                echo CHtml::closeTag('li') . "\n";
            else if ($category->level > $level)
                echo CHtml::openTag('ul') . "\n";
            else {
                echo CHtml::closeTag('li') . "\n";

                for ($i = $level - $category->level; $i; $i--) {
                    echo CHtml::closeTag('ul') . "\n";
                    echo CHtml::closeTag('li') . "\n";
                }
            }

            echo CHtml::openTag('li', array('id' => 'node_' . $category->primaryKey));
            echo CHtml::openTag('a', array('href' => '#', 'class'=>($category->id == $model->route_id ? 'selected' : '')));
            echo CHtml::encode($category->title);
            echo CHtml::closeTag('a');

            $level = $category->level;
        }

        for ($i = $level; $i; $i--) {
            echo CHtml::closeTag('li') . "\n";
            echo CHtml::closeTag('ul') . "\n";
        }
    ?>
</div>
<?php echo CHtml::error($model,'route_id'); ?>