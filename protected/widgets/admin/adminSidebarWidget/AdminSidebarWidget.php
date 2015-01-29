<?php
/**
 * Created by PhpStorm.
 * User: peter
 * E-mail: petro.stasuk.990@gmail.com
 * Date: 26.05.14
 * Time: 12:33
 */

class AdminSidebarWidget extends CWidget
{
    public $menuTitle;
    public $subMenuTitle;
    public $menu;
    public $subMenu;

    public function init()
    {
        $this->render('sidebar', array(
            'menuTitle' => $this->menuTitle,
            'subMenuTitle' => $this->subMenuTitle,
            'menu' => $this->menu,
            'subMenu' => $this->subMenu
        ));
    }
} 