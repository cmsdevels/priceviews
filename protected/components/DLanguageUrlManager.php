<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.11.13
 * Time: 14:20
 */

class DLanguageUrlManager extends CmsUrlManager
{
    public function createUrl($route, $params=array(), $ampersand='&')
    {
        $url = parent::createUrl($route, $params, $ampersand);
        return DMultilangHelper::addLangToUrl($url);
    }
} 