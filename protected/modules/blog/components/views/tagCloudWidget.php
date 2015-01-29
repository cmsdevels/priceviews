<h4 class="line">Облако тегов</h4>
<div class="tags_cloud">
<?php
    foreach($tagsNameSize as $tagNameSize){
    echo CHtml::link(
        $tagNameSize['name'],
        array('default/tag','name'=>CHtml::encode(trim($tagNameSize['name']))),
        array('class'=>'f1'.$tagNameSize['size'].($tagNameSize['size'] == 8 ? ' green' :''))
        );
    }
?>
</div>