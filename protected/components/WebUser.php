<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weblogic
 * Date: 6/13/12
 * Time: 4:18 PM
 * To change this template use File | Settings | File Templates.
 */
class WebUser extends CWebUser
{
    public $loginUrl=array('/user/login');

    public function getRole() {
        if ($this->hasState('role'))
            return $this->getState('role');
        else
            return 'guest';
    }

    public function getIsAdmin() {
        if ($this->hasState('is_admin'))
            return (bool)$this->getState('is_admin');
        else
            return false;
    }
}
