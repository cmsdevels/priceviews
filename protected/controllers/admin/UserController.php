<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weblogic
 * Date: 2/12/13
 * Time: 5:36 PM
 * To change this template use File | Settings | File Templates.
 */

class UserController extends CmsAdminController
{
    protected $_controllerName = 'Users';

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>Yii::t('core/admin','Manage users'),
                'create'=>Yii::t('core/admin','Create user'),
                'update'=>Yii::t('core/admin','Update user'),
                'delete'=>Yii::t('core/admin','Delete user'),
                'addRole'=>Yii::t('core/admin','Add role'),
                'updateRole'=>Yii::t('core/admin','Update role'),
                'roles'=>Yii::t('core/admin','Manage roles'),
            ),
        );
    }

    public function getMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin', 'Manage users'), 'url'=>array('index')),
            array('label'=>Yii::t('core/admin', 'Manage roles'), 'url'=>array('roles')),
        );
    }

    public function getSubMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin', 'Add user'), 'url'=>array('create')),
            array('label'=>Yii::t('core/admin', 'Add role'), 'url'=>array('addRole')),
        );
    }

    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new User('admin');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $model->scenario = 'admin';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionAddRole()
    {
        $model = new AuthItemForm('create');
        if (isset($_POST['AuthItemForm'])) {
            $model->attributes = $_POST['AuthItemForm'];
            $model->save();
        }
        $this->render('addRole', array('model'=>$model));
    }

    public function actionUpdateRole($name)
    {
        $model = new AuthItemForm('update');
        $model->loadItem($name);
        if (isset($_POST['AuthItemForm'])) {
            $model->attributes = $_POST['AuthItemForm'];
            $model->save($name);
        }
        $this->render('addRole', array('model'=>$model));
    }

    public function actionRoles()
    {
        $dataProvider = new CArrayDataProvider(array_values(Yii::app()->authManager->roles), array(
            'keyField'=>'name',
            'sort'=>array(
                'attributes'=>array(
                    'description', 'name'
                ),
            ),
        ));

        $this->render('roles', array('dataProvider'=>$dataProvider));
    }

    public function loadModel($id)
    {
        $model=User::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,Yii::t('core/admin', 'The requested page does not exist.'));
        return $model;
    }

}