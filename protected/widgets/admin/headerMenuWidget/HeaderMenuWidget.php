<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 27.05.14
 * Time: 10:56
 */

class HeaderMenuWidget extends CWidget
{
    public function init()
    {
        $modules = Module::model()->findAll();
        $this->render('menu', array('modules'=>$modules));
    }
} 