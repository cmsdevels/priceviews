<?php
return array(
    'name'=>'CategoryNewsWidget',
    'title'=>'CategoryNewsWidget',
    'description'=>'Category news widget',
    'version'=>'0.0.1',
    'author'=>array(
        'url'=>'http://web-logic.biz',
        'name'=>'Web-logic',
        'email'=>'i@web-logic.biz',
    ),
    'options'=>array(
        'ulClass'=>'',
    ),
    'editableAttributes'=>array(
        'ulClass'=>array(
            'name'=>'ulClass',
            'type'=>'string'
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