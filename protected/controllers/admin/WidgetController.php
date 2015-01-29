<?php

class WidgetController extends CmsAdminController
{
    protected $_controllerName = 'Widgets';

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>Yii::t('core/admin','Manage widgets'),
                'install'=>Yii::t('core/admin','Install widget'),
                'uninstall'=>Yii::t('core/admin','Uninstall widget'),
                'update'=>Yii::t('core/admin','Update widget'),
                'copy'=>Yii::t('core/admin','Copy widget'),
                'addUserWidget'=>Yii::t('core/admin','Creating a custom widget'),
                'listControllers'=>array(
                    'access'=>'isAdmin'
                ),
                'updateField'=>array(
                    'access'=>'isAdmin'
                ),
                'listMethods'=>array(
                    'access'=>'isAdmin'
                ),
                'positionList'=>array(
                    'access'=>'isAdmin'
                ),
            ),
        );
    }

    public function getMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin','Widgets'), 'url'=>array('index')),
            array('label'=> Yii::t('core/admin','Install Widget'), 'url'=>array('install')),
        );
    }

    public function getSubMenu()
    {
        return array(
            array('label'=> Yii::t('core/admin','Create user widget'), 'url'=>array('addUserWidget')),
        );
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $settingPosition = require(Yii::app()->theme->basePath.'/position.php');
        $listPosition = CHtml::listData($settingPosition, 'name', 'name');

        if ($model->type != Widget::TYPE_SYSTEM)
            $model->scenario = 'UserWidget';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Widget']))
        {
            $model->attributes=$_POST['Widget'];
            if($model->save())
                $this->redirect(array('index'));
        }

        if ($model->type == Widget::TYPE_SYSTEM)
            $this->render('update',array(
                'model'=>$model,
                'listPosition'=>$listPosition,
            ));
        else
            $this->render('updateUser',array(
                'model'=>$model,
                'listPosition'=>$listPosition,
            ));

    }

    public function actionCopy($id)
    {
        $widget = Widget::model()->findByPk($id);
        if ($widget == null)
            throw new CHttpException(404,Yii::t('core/admin', 'The requested page does not exist.'));
        else {

            $copyWidget =  clone $widget;
            $copyWidget->parent_id = $id;
            $copyWidget->save();
            $this->redirect(array('index'));
        }
    }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $model = new Widget();
        $model->unsetAttributes();
        if(isset($_GET['Widget']))
            $model->attributes=$_GET['Widget'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

    public function actionAddUserWidget()
    {
        $model = new Widget();
        $model->scenario = 'UserWidget';

        $settingPosition = require(Yii::app()->theme->basePath.'/position.php');
        $listPosition = CHtml::listData($settingPosition, 'name', 'name');
        if (isset($_POST['Widget'])) {
            $model->attributes = $_POST['Widget'];
            $model->name = 'UserWidget';
            if ($model->save())
                $this->redirect(array('index'));
        }
        $this->render('userWidget',array('model'=>$model, 'listPosition'=>$listPosition));
    }

	/**
	 * Install.
	 */
    public function actionInstall($action='',$widget='')
    {
        // Widget installation
        if ($action=='install')
        {
            if (($resultInstall = Widget::install($widget)) === true)
            {
                Yii::app()->user->setFlash('success',Yii::t('core/admin', 'Widget').' "'.ucfirst($widget).'" '.Yii::t('core/admin','has been successful installed.'));
                $this->redirect(array('install'));
            } else {
                Yii::app()->user->setFlash('error',Yii::t('core/admin', Widget::prepareErrorMessage($resultInstall)));
                $this->redirect(array('install'));
            }
        }
        $dataProvided= Widget::widgetsForInstall();

        $this->render('install',array(
            'dataProvided'=>$dataProvided,
        ));
    }

    public function actionUninstall($id)
    {
        $model = $this->loadModel($id);
        // Widget uninstallation
        if ($model->delete())
            Yii::app()->user->setFlash('success',Yii::t('core/admin', "Widget uninstallation success."));
        else
            Yii::app()->user->setFlash('error',Yii::t('core/admin', "Widget uninstallation error."));

        $this->redirect(array('index'));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Widget::model()->multilang()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('core/admin', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='module-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionListControllers()
    {
        if(isset($_POST['Widget'])) {
            $requestData = $_POST['Widget'];
            $controllers = Module::listControllers($requestData['module']);
            echo CHtml::tag('option',
                array('value'=>''), Yii::t('core/admin', 'None'), true);
            foreach($controllers as $key=>$file) {
                echo CHtml::tag('option',
                    array('value'=>$key),CHtml::encode($file), true);
            }
        } else
            throw new CHttpException(404,Yii::t('core/admin', 'The requested page does not exist.'));

    }

    public function actionListMethods()
    {
        if(isset($_POST['Widget'])) {
            $requestData = $_POST['Widget'];
            $methods = Module::listMethods($requestData['module'], $requestData['controller']);
            echo CHtml::tag('option',
                array('value'=>''), Yii::t('core/admin', 'None'), true);
            foreach($methods as $key=>$method) {
                echo CHtml::tag('option',
                    array('value'=>$key),CHtml::encode($method),true);
            }
        } else
            throw new CHttpException(404,Yii::t('core/admin', 'The requested page does not exist.'));
    }

    public function actionUpdateField()
    {
        Yii::import('ext.bootstrap.widgets.TbEditableSaver');
        $es = new TbEditableSaver('Widget');
        $es->update();
    }

    public function actionStatusList()
    {
        $statuses = array(
            array('value' => Widget::STATUS_DISABLE, 'text'=>'Не доступный'),
            array('value' => Widget::STATUS_ENABLE, 'text'=>'Доступный'),
        );
        echo CJSON::encode($statuses);
    }

    public function actionPositionList()
    {
        $settingPosition = require(Yii::app()->theme->basePath.'/position.php');
        $listPosition = array();
        foreach ($settingPosition as $position)
            $listPosition[] = array('value'=>$position['name'], 'text'=>$position['name']);
        echo CJSON::encode($listPosition);
    }
}
