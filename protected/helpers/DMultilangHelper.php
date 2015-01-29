<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.11.13
 * Time: 14:17
 */

class DMultilangHelper
{
    public static function enabled()
    {
        return count(Language::getListLanguages()) > 1;
    }

    public static function suffixList($checkEnabled = true)
    {
        $list = array();
        if($checkEnabled) {
            $enabled = self::enabled();
        } else {
            $enabled = true;
        }
        $models = CHtml::listData(Language::model()->findAll(array('order'=>'status DESC')), 'name', 'title');
        foreach ($models as $lang => $name)
        {
            if ($lang === Language::getDefaultLanguage()->name) {
                $suffix = '';
                $list[$suffix] = $enabled ? $name : '';
            } else {
                $suffix = '_' . $lang;
                $list[$suffix] = $name;
            }
        }

        return $list;
    }

    public static function processLangInUrl($url)
    {
        if (self::enabled())
        {
            $domains = explode('/', ltrim($url, '/'));

            $isLangExists = in_array($domains[0], array_keys(Language::getListLanguages()));
            $isDefaultLang = $domains[0] == Language::getDefaultLanguage()->name;

            if ($isLangExists && !$isDefaultLang)
            {
                $lang = array_shift($domains);
                Yii::app()->setLanguage($lang);
            }

            $url = '/' . implode('/', $domains);
        }

        return $url;
    }

    public static function addLangToUrl($url)
    {
        if (self::enabled())
        {
            $domains = explode('/', ltrim($url, '/'));
            $isHasLang = in_array($domains[0], array_keys(Language::getListLanguages()));
            $isDefaultLang = Yii::app()->getLanguage() == Language::getDefaultLanguage()->name;

            if ($isHasLang && $isDefaultLang)
                array_shift($domains);

            if (!$isHasLang && !$isDefaultLang)
                array_unshift($domains, Yii::app()->getLanguage());

            $url = '/' . implode('/', $domains);
        }

        return $url;
    }
} 