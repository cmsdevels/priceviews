<?php
return array(
    'name'=>'LastNewsWidget',
    'title'=>'LastNewsWidget',
    'description'=>'Last news widget',
    'version'=>'0.0.1',
    'author'=>array(
        'url'=>'http://web-logic.biz',
        'name'=>'Web-logic',
        'email'=>'i@web-logic.biz',
    ),
    'options'=>array(
        'countNews'=>5,
    ),
    'editableAttributes'=>array(
        'countNews'=>array(
            'name'=>'countNews',
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