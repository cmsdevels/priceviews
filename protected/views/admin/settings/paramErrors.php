<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'fade' => false,
    'closeText' => '&times;',
    'events' => array(),
    'htmlOptions' => array('id' => 'param-errors'),
    'alerts' => array(
        'danger' => array('closeText' => '&times;'),
    ),
));
