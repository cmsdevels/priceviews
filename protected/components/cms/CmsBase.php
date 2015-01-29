<?php
/**
 * CmsBase.php.
 * @author Yuriy Firs <firs.yura@gmail.com>
 */
class CmsBase extends YiiBase
{
    public static function getVersion()
    {
        return '0.0.1';
    }

    public static function getYiiVersion()
    {
        return parent::getVersion();
    }

    public function checkExtension($name='')
    {
        return false;
    }

}
