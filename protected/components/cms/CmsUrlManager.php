<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weblogic
 * Date: 1/25/12
 * Time: 3:43 PM
 * To change this template use File | Settings | File Templates.
 */
class CmsUrlManager extends CUrlManager
{
    protected $_cmsRules = array();
    protected $_dbRules = array();
    private $_rules=array();

    public function init()
    {
        $this->parseModulesRoute();
        $this->processRules();
        parent::init();
    }

    protected function processRules()
    {
        if(empty($this->rules) || $this->getUrlFormat()===self::GET_FORMAT)
            return;
        if($this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
        {
            $hash=md5(serialize($this->rules));
            if(($data=$cache->get(self::CACHE_KEY))!==false && isset($data[1]) && $data[1]===$hash)
            {
                $this->_rules=$data[0];
                return;
            }
        }
        foreach($this->rules as $pattern=>$route)
            $this->_rules[]=$this->createUrlRule($route,$pattern);
        if(isset($cache))
            $cache->set(self::CACHE_KEY,array($this->_rules,$hash));
    }

    public function parseModulesRoute()
    {
        $order = array();
        $rules = array();
        foreach (Yii::app()->modules as $key=>$value)
        {
            $routeFile = YiiBase::getPathOfAlias('application.modules.'.$key).DIRECTORY_SEPARATOR."route.php";
            if (is_file($routeFile))
            {
                $route = require($routeFile);
                if (isset($route['order']) && isset($route['rules'])) {
                    $order[] = $route['order'];
                    $rules[] = $route['rules'];
                }
            }
        }
        array_multisort($order, SORT_DESC, $rules);

        foreach($rules as $rule)
        {
            foreach($rule as $key=>$value)
            {
                $this->rules[$key] = $value;
            }
        }
    }



    public function parseUrl($request)
    {
        if($this->getUrlFormat()===self::PATH_FORMAT)
        {
            $rawPathInfo=$request->getPathInfo();
            $pathInfo=$this->removeUrlSuffix($rawPathInfo,$this->urlSuffix);
            foreach($this->_rules as $i=>$rule)
            {
                if(is_array($rule))
                    $this->_rules[$i]=$rule=Yii::createComponent($rule);
                if(($r=$rule->parseUrl($this,$request,$pathInfo,$rawPathInfo))!==false)
                    return isset($_GET[$this->routeVar]) ? $_GET[$this->routeVar] : $r;
            }

            $dbRoute = Route::model()->findByAttributes(array(
                'full_url'=>$pathInfo
            ));

            if ($dbRoute !== null) {
                $_GET['id'] = $dbRoute->item_id;
                return $dbRoute->current_name;
            }

            throw new CHttpException(404,Yii::t('yii','Unable to resolve the request "{route}".',
                    array('{route}'=>$pathInfo)));
        }
        elseif(isset($_GET[$this->routeVar]))
            return $_GET[$this->routeVar];
        elseif(isset($_POST[$this->routeVar]))
            return $_POST[$this->routeVar];
        else
            return '';
    }

    public function createUrl($route,$params=array(),$ampersand='&')
    {
        unset($params[$this->routeVar]);

        $url = $this->createDbUrl($route, $params, $ampersand);
        if ($url !== false)
            return '/'.$url;

        foreach($params as $i=>$param)
            if($param===null)
                $params[$i]='';

        if(isset($params['#']))
        {
            $anchor='#'.$params['#'];
            unset($params['#']);
        }
        else
            $anchor='';
        $route=trim($route,'/');
        foreach($this->_rules as $i=>$rule)
        {
            if(is_array($rule))
                $this->_rules[$i]=$rule=Yii::createComponent($rule);
            if(($url=$rule->createUrl($this,$route,$params,$ampersand))!==false)
            {
                if($rule->hasHostInfo)
                    return $url==='' ? '/'.$anchor : $url.$anchor;
                else
                    return $this->getBaseUrl().'/'.$url.$anchor;
            }
        }
        return $this->createUrlDefault($route,$params,$ampersand).$anchor;
    }

    protected function createDbUrl($route, $params, $ampersand='&')
    {
        if (isset($params['id'])) {
            $url = Route::getFullUrl($route, $params['id']);
            if ($url !== false) {
                unset($params['id']);
                if (!empty($params)) {
                    $anchor='';
                    if(isset($params['#']))
                    {
                        $anchor='#'.$params['#'];
                        unset($params['#']);
                    }
                    if (!empty($params)) {
                        $url .= '?';
                        foreach ($params as $key=>$value) {
                            $url .= ($key.'='.$value.$ampersand);
                        }
                        $url = trim ($url, '&');
                    }

                }
                return $url;
            }
        }
        return false;
    }

}
