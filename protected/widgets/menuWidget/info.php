<?php
return array(
    'name'=>'MenuWidget',
    'title'=>'Menu',
    'description'=>'Site menu',
    'version'=>'0.0.1',
    'author'=>array(
        'url'=>'http://web-logic.biz',
        'name'=>'Web-logic',
        'email'=>'i@web-logic.biz',
    ),
    'options'=>array(
        'menu'=>null,
    ),
    'editableAttributes'=>array(
        'menu'=>array(
            'name'=>'Menu',
            'type'=>'select',
            'model'=>'Menu',
            'valueAttribute' =>'id',
            'titleAttribute' => 'title',
            'import'=>'application.models.Menu',
            'criteria'=>array('route_id'=>null),
        ),
    ),
);