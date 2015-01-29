<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.11.13
 * Time: 14:16
 */

class HttpRequest extends CHttpRequest
{
    private $_requestUri;

    public function getRequestUri()
    {
        if ($this->_requestUri === null)
            $this->_requestUri = DMultilangHelper::processLangInUrl(parent::getRequestUri());

        return $this->_requestUri;
    }

    public function getOriginalUrl()
    {
        return $this->getOriginalRequestUri();
    }

    public function getOriginalRequestUri()
    {
        return DMultilangHelper::addLangToUrl($this->getRequestUri());
    }
} 