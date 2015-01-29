<?php
return array(
    'name'=>'LastCommentsWidget',
    'title'=>'LastCommentsWidget',
    'description'=>'Last comments widget',
    'version'=>'0.0.1',
    'author'=>array(
        'url'=>'http://web-logic.biz',
        'name'=>'Web-logic',
        'email'=>'i@web-logic.biz',
    ),
    'options'=>array(
        'countComments'=>5,
    ),
    'editableAttributes'=>array(
        'countComments'=>array(
            'name'=>'countComments',
            'type'=>'number'
        ),
    ),
    'depends'=>array(
        'modules'=>array(
            'news'=>array(
                'version'=>'0.0.1'
            ),
        ),
    ),
);