<?php
return array(
    'order'=>'2',
    'rules'=>array(
        '/gii'=>'/gii/default/index',
        '/gii/<controller:\w+>/<id:\d+>'=>'/gii/<controller>/view',
        '/gii/<controller:\w+>'=>'/gii/<controller>',
        '/gii/<controller:\w+>/<action:\w+>/<id:\d+>'=>'/gii/<controller>/<action>',
        '/gii/<controller:\w+>/<action:\w+>'=>'/gii/<controller>/<action>',
    )
);