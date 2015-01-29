<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class CmsController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
    public $title;

    public function getMenuTitle()
    {
        return Yii::t('core/admin', 'Main menu');
    }

    public function getMenu()
    {
        return array();
    }

    public function getSubMenuTitle()
    {
        return Yii::t('core/admin', 'Submenu');
    }

    public function getSubMenu()
    {
        return array();
    }
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();



    public function position($name)
    {
        $this->widget('application.components.cms.CmsPositionWidget', array('position'=>$name));
    }

    public static function listBaseControllers()
    {
        $controllers = array(
            'PageController',
            'UserController',
        );
        return $controllers;
    }

    public function accessSpecs()
    {
        return array(
            'operations' => array()
        );
    }

    protected $_controllerName;

    public function getControllerName()
    {
        if (isset($this->_controllerName))
            return Yii::t('core/admin', $this->_controllerName);
        else
            return Yii::t('core/admin', get_class($this));
    }

    public $_model = null;

    public function getSeoTitle()
    {
        $separator = Yii::app()->params['defaultSeparator'];
        $defaultTitle = Yii::app()->params['defaultTitle'];
        if(Yii::app()->hasModule('seotext')!==false && ($seoTitle=Yii::app()->getModule('seotext')->seoPageTitle())!==false){
            return $seoTitle.$separator.$defaultTitle;
        }elseif ($this->_model !== null) {
            /**@var $metaData MetaData*/
            $metaData = MetaData::model()->findByAttributes(array(
                'model'=>get_class($this->_model),
                'model_id'=>$this->_model->id
            ));
            if ($metaData !== null) {
                Yii::app()->clientScript->registerMetaTag($metaData->meta_keywords, 'keywords');
                Yii::app()->clientScript->registerMetaTag($metaData->meta_description, 'description');
            }
            return isset($metaData->meta_title) ? $metaData->meta_title.$separator.$defaultTitle : $defaultTitle;
        }else{
            return $defaultTitle;
        }
    }
}