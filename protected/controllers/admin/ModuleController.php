<?php

class ModuleController extends CmsAdminController
{
    protected $_controllerName = 'Modules';

    public function accessSpecs()
    {
        return array(
            'operations'=>array(
                'index'=>Yii::t('core/admin','Manage modules'),
                'install'=>Yii::t('core/admin','Install module'),
                'uninstall'=>Yii::t('core/admin','Uninstall module'),
                'update'=>Yii::t('core/admin','Update module'),
            ),
        );
    }

    public function getMenu()
    {
        return array(
            array('label'=>Yii::t('core/admin','Modules'), 'url'=>array('index')),
            array('label'=> Yii::t('core/admin','Install Module'), 'url'=>array('install')),
        );
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Module']))
		{
			$model->attributes=$_POST['Module'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Module');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Install.
	 */
    public function actionInstall($action='',$module='', $id=null)
    {
        $module = ($module ? strtolower($module) : '');
        // Module installation
        if ($action=='install')
        {
            if (Module::install($module, null, $id)==true)
            {
                Yii::app()->user->setFlash('success',Yii::t('core/admin','Module').' "'.ucfirst($module).'" '.Yii::t('core/admin','has been successful installed.'));
                $this->redirect(array('install'));
            } else {
                Yii::app()->user->setFlash('error',Yii::t('core/admin',"Module installation error.")."<br/>".Yii::app()->getModule($module)->installError);
                $this->redirect(array('install'));
            }
        } elseif ($action=='upgrade') {
            if (Module::upgrade($module)==true)
            {
                Yii::app()->user->setFlash('success',Yii::t('core/admin','Module').' "'.ucfirst($module).'" '.Yii::t('core/admin','has been successful updated.'));
                $this->redirect(array('install'));
            } else {
                Yii::app()->user->setFlash('error',Yii::t('core/admin',"Module update error.")."<br/>".Yii::app()->getModule($module)->installError);
                $this->redirect(array('install'));
            }
        }

        $dataProvided= Module::modulesForInstall();
        $installModel = new InstallModuleForm();

        $this->render('install',array(
            'dataProvided'=>$dataProvided,
            'installModel'=>$installModel
        ));
    }

    public function actionInstallArchive()
    {
        $model = new InstallModuleForm();
        if (isset($_POST['InstallModuleForm'])) {
            $model->attributes = $_POST['InstallModuleForm'];
            if ($model->validate() && $model->install())
                Yii::app()->user->setFlash('success',Yii::t('core/admin','Module').' "'.ucfirst($model->name).'" '.Yii::t('core/admin','has been successful updated.'));
            else
                Yii::app()->user->setFlash('error',Yii::t('core/admin',"Module install error.")."<br/>");
        }
        $this->redirect(array('install'));
    }

    public function actionUninstall($id)
    {
        $model = $this->loadModel($id);
        // Module uninstallation
        if (!method_exists(Yii::app()->getModule(strtolower($model->title)), 'unistall') || Yii::app()->getModule(strtolower($model->title))->uninstall()) {
            Module::rrmdir(YiiBase::getPathOfAlias('application.modules.'.strtolower($model->name)));
            $model->delete();
        }
        else
            Yii::app()->user->setFlash('error',Yii::t('core/admin',"Module uninstallation error.")."<br/>".Yii::app()->getModule(strtolower($model->title))->installError);

        $this->redirect(array('index'));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Module::model()->findByPk($id);
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
}
