<?php
/**
 * Created by PhpStorm.
 * User: Sergiy
 * Company: http://web-logic.biz/
 * Email: sirogabox@gmail.com
 * Date: 02.06.14
 * Time: 12:03
 */
Yii::import('bootstrap.widgets.*');
Yii::import('application.components.cms.cmsGridView.*');
class TbExtendedGridViewCms extends TbExtendedGridView{

    const UPSS = 'user_pss';
    /**
     *  model name variable
     *  @access private
     *  @see TbExtendedGridViewCms()
     *  @uses init, _updatePageSize
     *  @var string
     */
    private $_modelName;
    /**
     * @var array Page sizes available to set for web-user.
     */
    public $pageSizes = array(5,10,20,50,100);

    /**
     * @var string A name for query parameter, that stores page size specified by web-user.
     */
    public $pageSizeVarName = 'pageSize';

    /**
     * @var bool Whether rendering of page size selector at headline section is available and enabled.
     */
    protected $_pageSizesEnabled = false;

    /**
     *  Value of headlinePosition:
     *  @uses renderHeadline
     *  @var string
     **/
    public $headlinePosition;

    public $template = "{summary}\n{items}\n{headline}\n{pager}\n{extendedSummary}";

    public function init()
    {
        if($this->dataProvider instanceof CActiveDataProvider){
            $this->_modelName = $this->dataProvider->modelClass;
        }else{
            $this->_modelName = 'default';
        }

        if (preg_match('/extendedsummary/i', $this->template) && !empty($this->extendedSummary) && isset($this->extendedSummary['columns']))
        {
            $this->template .= "\n{extendedSummaryContent}";
            $this->displayExtendedSummary = true;
        }
        if (!empty($this->chartOptions) && $this->chartOptions['data'] && $this->dataProvider->getItemCount())
        {
            $this->displayChart = true;
        }
        if ($this->bulkActions !== array() && isset($this->bulkActions['actionButtons']))
        {
            if(!isset($this->bulkActions['class']))
                $this->bulkActions['class'] = 'application.components.cms.cmsGridView.TbBulkActionsCms';

            $this->bulk = Yii::createComponent($this->bulkActions, $this);
            $this->bulk->init();
        }
        $this->initPageSizes();
        parent::init();
    }

    /**
     * Sets "pageSize" parameter at instance of CPagination which belongs to data provider.
     * The value to set or specified by the web-user or taken from the session, where it is being stored when user specifies it.
     *
     * @return void
     */
    protected function initPageSizes()
    {
        $modSettings = Yii::app()->user->getState(self::UPSS, null);
        $pagination = $this->dataProvider->getPagination();
        if (
            !$this->enablePagination
            || strpos($this->template, '{headline}') === false
            || $pagination === false
            || $this->dataProvider->getTotalItemCount()==0
        ) {
            $this->_pageSizesEnabled = false;
        } else {
            $this->_pageSizesEnabled = true;
            // Web-user specifies desired page size.
            if (($pageSizeFromRequest = Yii::app()->getRequest()->getParam($this->pageSizeVarName)) !== null) {
                $pageSizeFromRequest = (int)$pageSizeFromRequest;
                // Check whether given page size is valid or use default value
                if (in_array($pageSizeFromRequest, $this->pageSizes)) {
                    $pagination->pageSize = $pageSizeFromRequest;
                }
                $this->_updatePageSize();
            }
            // Check for value at session or use default value
            elseif(isset($modSettings[strtolower($this->_modelName)]['pageSize'])) {
                $pagination->pageSize = $modSettings[strtolower($this->_modelName)]['pageSize'];
            }

        }
    }

    /**
     * Headline rendering method
     * @return void
     */
    public function renderHeadline()
    {
        if (!$this->_pageSizesEnabled) {
            return;
        }
        /** @var $cs CClientScript */
        $cs = Yii::app()->getClientScript();

        $dropDownListValues = array();
        $currentPageSize = $this->dataProvider->getPagination()->pageSize;
        /* Formation of switches: */
        foreach($this->pageSizes as $key=>$pageSize){
            $dropDownListValues[$pageSize]=$pageSize;
        }

        $headlinePosition = '';
        if (in_array($this->headlinePosition, array('left', 'right'))) {
            $headlinePosition = ' style="text-align: ' . $this->headlinePosition . ';" ';
        }
        echo '<div class="headline" ' . $headlinePosition .' >';
        /* Display text: */
        echo Yii::t('core/admin', 'Count items').':&nbsp;&nbsp;&nbsp;&nbsp;';
        /* Displaying switches PageSize dropDownList*/
        echo CHtml::dropDownList('gridPageSize',$currentPageSize,$dropDownListValues,array('id'=>$this->getId().'_countPage'));
        echo '</div>';
        /* JS script transmission PageSize*/
        $cs->registerScript(
            __CLASS__ . '#' . $this->id . 'ExHeadlineGridView',
<<<JS
            (function(){
                $('body').on('change','#{$this->getId()}_countPage',function(){
                    $('#{$this->getId()}').yiiGridView('update',{
                        url: window.location.href,
                        data: {
                            '{$this->pageSizeVarName}': $(this).val()
                        }
                    });
                });
        })();
JS
            , CClientScript::POS_READY
        );
    }

    /**
     * Updating the number of entries in the session
     * @return void
     */
    protected function _updatePageSize()
    {
        $modelName = strtolower($this->_modelName);
        $sessionSettings = Yii::app()->user->getState(self::UPSS, null);
        $currentPageSize = $this->dataProvider->getPagination()->pageSize;
        if(!isset($sessionSettings[$modelName]['pageSize'])||$sessionSettings[$modelName]['pageSize']!==$currentPageSize){
            $sessionSettings[$modelName]['pageSize'] = $currentPageSize;
            Yii::app()->user->setState(self::UPSS, $sessionSettings);

        }
    }

} 