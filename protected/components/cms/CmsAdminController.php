<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class CmsAdminController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/user.php'.
	 */
	public $layout='//admin/layouts/furia';

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

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'adminLogin',
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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

    public function accessSpecs()
    {
        return array(
            'operations'=>array(),
        );
    }

    public function filterAdminLogin($filterChain)
    {
        Yii::app()->user->loginUrl = array('/admin/default/login');
        $filterChain->run();
    }

    public function filterAccessControl($filterChain)
    {
        $filter=new CmsAccessControlFilter;
        $filter->filter($filterChain);
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $BootstrapConfig = array(
                'class' => 'ext.bootstrap.components.Bootstrap',
                'responsiveCss' => false,
                'coreCss' => false,
                'yiiCss' => false,
                'jqueryCss' => false,
            );
            Yii::app()->setComponent('bootstrap',Yii::CreateComponent($BootstrapConfig));
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/admin/bootstrap/css/bootstrap.min.css');
            return true;
        } else
            return false;
    }
}