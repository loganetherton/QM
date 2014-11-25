<?php
/**
 * Pagination class file.
 *
 * @author Dawid Majewski <mail@dragooon.net>
 */

class Pagination extends CPagination
{
    //Force passing the first page parameter
    public $forceFirstPageVar = false;

    /**
     * Creates the URL suitable for pagination.
     * This method is mainly called by pagers when creating URLs used to
     * perform pagination. The default implementation is to call
     * the controller's createUrl method with the page information.
     * You may override this method if your URL scheme is not the same as
     * the one supported by the controller's createUrl method.
     * @param CController $controller the controller that will create the actual URL
     * @param integer $page the page that the URL should point to. This is a zero-based index.
     * @return string the created URL
     */
    public function createPageUrl($controller,$page)
    {
        $params=$this->params===null ? $_GET : $this->params;
        if($page>0||($page==0 && $this->forceFirstPageVar)) // page 0 is the default
            $params[$this->pageVar]=$page+1;
        else
            unset($params[$this->pageVar]);
        return $controller->createUrl($this->route,$params);
    }
}