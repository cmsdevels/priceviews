<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weblogic
 * Date: 2/12/13
 * Time: 5:36 PM
 * To change this template use File | Settings | File Templates.
 */

class UserController extends CmsController {

    protected $_controllerName = 'User';

    public function accessSpecs()
    {
        return array(
            'operations' => array(
                'login' => Yii::t('core/admin', 'Login page')
            )
        );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('login'),
                'users'=>array('*'),
            ),
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('logout'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (!empty(Yii::app()->request->urlReferrer) && Yii::app()->request->urlReferrer != $this->createAbsoluteUrl("/user/login")) {
            Yii::app()->user->setReturnUrl(Yii::app()->request->urlReferrer);
        }

        $model=new LoginForm();

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }

        // display the login form
        $this->render('login', array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect.
     */
    public function actionLogout()
    {
        if (!empty(Yii::app()->request->urlReferrer) && Yii::app()->request->urlReferrer != $this->createAbsoluteUrl("/user/logout")) {
            Yii::app()->user->setReturnUrl(Yii::app()->request->urlReferrer);
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->returnUrl);
    }
}