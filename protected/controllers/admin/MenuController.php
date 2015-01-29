<?php

class MenuController extends CmsAdminController
{
    protected $_controllerName = 'Menu';

    private $_behaviorIDs = array();

    public function createAction($actionID)
    {
        $action = parent::createAction($actionID);
        if ($action !== null)
            return $action;
        foreach ($this->_behaviorIDs as $behaviorID) {
            $object = $this->asa($behaviorID);
            if ($object->getEnabled() && method_exists($object, 'action' . $actionID))
                return new CInlineAction($object, $actionID);
        }
    }

    public function attachBehavior($name, $behavior)
    {
        $this->_behaviorIDs[] = $name;
        parent::attachBehavior($name, $behavior);
    }

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>Yii::t('core/admin','Manage Menu'),
                'create'=>Yii::t('core/admin','Add menu'),
                'update'=>Yii::t('core/admin','Update menu'),
                'delete'=>Yii::t('core/admin','Delete menu'),
                'createItem'=>Yii::t('core/admin','Add menu item'),
                'updateItem'=>Yii::t('core/admin','Update menu item'),
                'moveItem'=>Yii::t('core/admin','Move menu item'),
            ),
        );
    }

    public function getMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin', 'Manage Menu'), 'url'=>array('/admin/menu/index')),
        );
    }

    public function getSubMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin', 'Add menu'), 'url'=>array('/admin/menu/create')),
            array('label'=>Yii::t('core/admin', 'Add menu item'), 'url'=>array('/admin/menu/createItem')),
        );
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Menu('root');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			if($model->saveNode())
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
        $model->scenario = 'root';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			if($model->saveNode())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

    public function actionCreateItem()
    {
        $model = new Menu('childItem');
        if(isset($_POST['Menu']))
        {
            $model->attributes=$_POST['Menu'];
            if($model->validate()){
                $parent = Menu::model()->findByPk($model->parent_id);
                if ($parent===null)
                    throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));

                if($model->appendTo($parent))
                    $this->redirect(array('index'));
            }
        }
        $this->render('createItem',array(
            'model'=>$model,
        ));


    }

    public function actionUpdateItem($id)
    {
        $model=$this->loadModel($id);
        $model->scenario = 'childItem';
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if ($model->parent !== null)
            $model->parent_id = $model->parent->id;
        if(isset($_POST['Menu']))
        {
            $model->attributes=$_POST['Menu'];
            $parent = Menu::model()->findByPk($model->parent_id);
            if ($parent===null)
                throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));

            if($model->saveNode()) {
                if ($model->parent_id != $model->parent->id)
                    $model->moveAsLast($parent);
                $this->redirect(array('update', 'id' => $model->root));
            }
        }

        $this->render('updateItem',array(
            'model'=>$model,
        ));
    }

    public function actionMoveItem()
    {
        if (isset($_POST['id'])&&isset($_POST['parent_id'])&&isset($_POST['prev_id'])) {

            $model = Menu::model()->findByPk($_POST['id']);
            if ($model===null)
                throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));

            if ($_POST['parent_id']==$_POST['prev_id']) {
                $parent = Menu::model()->findByPk($_POST['parent_id']);
                if ($parent===null)
                    throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));

                $model->moveAsFirst($parent);
            } else {
                $prev = Menu::model()->findByPk($_POST['prev_id']);
                if ($prev===null)
                    throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));

                $model->moveAfter($prev);
            }

            $model->parent_id = $model->parent->id;
            $model->saveNode();

        }

    }

    public function actionDeleteItem($id)
    {
        $model = $this->loadModel($id);
        $root = $model->root;
        $model->deleteNode();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(array('update', 'id'=>$root));
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->deleteNode();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Menu('adminSearchMenu');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Menu']))
			$model->attributes=$_GET['Menu'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Menu the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Menu::model()->multilang()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('core/admin', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Menu $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='Menu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function behaviors()
    {
        return array(
            'jsTreeBehavior' => array(
                'class' => 'application.extensions.yii-jstree.behaviors.JsTreeBehavior',
                'modelClassName' => 'Menu',
                'label_property' => 'title',
                'rel_property' => 'title',
            )
        );
    }

    public function actionGetTypeMenuForm()
    {
        $type = Yii::app()->request->getParam('type');
        $menuItemId = Yii::app()->request->getParam('menu_item_id');
        $model = null;
        if ($menuItemId)
            $model = Menu::model()->findByPk($menuItemId);
        if ($model === null)
            $model = new Menu();
        $_form = null;
        switch ($type) {
            case Menu::TYPE_LINK:
                $_form = $this->renderPartial('_link', array('model'=>$model), true);
                break;
            case Menu::TYPE_ROUTE:
                $_form = $this->renderPartial('_route', array('model'=>$model), true);
                break;
            case Menu::TYPE_ROUTE_MODULE:
                $_form = $this->renderPartial('_route_module', array('model'=>$model), true);
                break;
        }
        echo $_form;
        Yii::app()->end();
    }

    public function actionModuleRoutes()
    {
        $moduleName = Yii::app()->request->getParam('module');
        $routes = Module::getRoutes($moduleName);
        echo CHtml::tag('option',
            array('value'=>''),Yii::t('core/admin', 'Select link'),true);
        foreach ($routes as $url=>$route) {
            echo CHtml::tag('option',
                array('value'=>$url),$url,true);
        }
        Yii::app()->end();
    }
}
