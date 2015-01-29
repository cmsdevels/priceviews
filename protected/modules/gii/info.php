<?php
return array(
    'title'=>'Gii',
    'description'=>'Gii module installer',
    'version'=>'1.0.0',
    'author'=>array(
        'url'=>'http://web-logic.biz',
        'name'=>'Web-logic',
        'email'=>'i@web-logic.biz',
    ),
    'admin_controller'=>'/default/index',
    'options'=>array(
        'class'=>'system.gii.GiiModule',
        'password'=>'0000',
        'ipFilters'=>array('*','::1'),
    ),
    'editableAttributes'=>array(
        'class'=>array(
            'name'=>'Class',
            'type'=>'string'),
        'password'=>array(
            'name' => 'Password',
            'type' => 'password',),

        'ipFilters'=>array(
            'blockTitle' => 'Ip filters',
            array(
                'name'=>'IP',
                'type'=>'string',
            ),
            array(
                'name'=> 'IP',
                'type'=>'string'
            )
        )
    )
);