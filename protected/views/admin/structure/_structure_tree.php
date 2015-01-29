<?php
/**
 * @var Route[] $routes
 */
?>

<?php
    $level = 0;
    foreach ($routes as $n => $route) {

        if ($route->level == $level)
            echo CHtml::closeTag('li') . "\n";
        else if ($route->level > $level)
            echo CHtml::openTag('ul') . "\n";
        else {
            echo CHtml::closeTag('li') . "\n";

            for ($i = $level - $route->level; $i; $i--) {
                echo CHtml::closeTag('ul') . "\n";
                echo CHtml::closeTag('li') . "\n";
            }
        }

        echo CHtml::openTag('li', array('id' => 'node_' . $route->primaryKey, 'data-id'=>$route->id, 'rel' => $route->haveChild));
        echo CHtml::openTag('a', array('href' => '#'));
        echo CHtml::encode($route->title);

        echo CHtml::openTag('span', array('class'=>'actions'));
        echo CHtml::openTag('span', array('class'=>'view fui-eye', 'data-href'=>$route->viewItemUrl, 'title'=>Yii::t('core/admin', 'View'))).CHtml::closeTag('span');
        echo CHtml::openTag('span', array('class'=>'edit fui-new', 'data-href'=>$route->getAdminItemUrl(), 'title'=>Yii::t('core/admin', 'Edit'))).CHtml::closeTag('span');
        echo CHtml::openTag('span', array('class'=>'delete fui-cross', 'data-href'=>$route->getAdminItemUrl('delete'), 'title'=>Yii::t('core/admin', 'Delete'))).CHtml::closeTag('span');
        echo CHtml::closeTag('span');

        echo CHtml::closeTag('a');

        $level = $route->level;
    }

    for ($i = $level; $i; $i--) {
        echo CHtml::closeTag('li') . "\n";
        echo CHtml::closeTag('ul') . "\n";
    }
?>

<?php if (empty($routes)) { ?>
    <div class="empty">
        <?php echo Yii::t('core/admin', 'The site structure is empty'); ?>
    </div>
<?php } ?>