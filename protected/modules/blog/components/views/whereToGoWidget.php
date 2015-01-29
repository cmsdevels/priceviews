<?php
/* @var $ulClass string */
/* @var $title string */
/* @var $postCats PostCat */
?>
<?php echo $title; ?>
<ul class="<?php echo $ulClass; ?>">
    <?php
        foreach ($postCats as $postCat) {
            echo "<li>".CHtml::link($postCat->name, array("/blog/default/view", "seo_link"=>$postCat->seo_link))."</li>";
        }
    ?>
</ul>